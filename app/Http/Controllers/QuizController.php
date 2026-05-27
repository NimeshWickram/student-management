<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Teacher;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser as PdfParser;

class QuizController extends Controller
{
    /**
     * Display a listing of the quizzes.
     */
    public function index(Request $request)
    {
        $tenantId = $this->getActiveTenantId();
        $query = Quiz::where('tenant_id', $tenantId)->with(['teacher', 'subject']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('grade', 'like', "%{$search}%")
                  ->orWhereHas('teacher', function ($q2) use ($search) {
                      $q2->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('subject', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                  });
            });
        }

        $quizzes = $query->latest()->paginate(10);

        return view('quizzes.index', compact('quizzes'));
    }

    /**
     * Show the form for creating a new quiz.
     */
    public function create()
    {
        $tenantId = $this->getActiveTenantId();
        $teachers = Teacher::where('tenant_id', $tenantId)->orderBy('first_name')->get();
        $subjects = Subject::where('tenant_id', $tenantId)->orderBy('name')->get();

        return view('quizzes.create', compact('teachers', 'subjects'));
    }

    /**
     * Store a newly created quiz in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id',
            'title' => 'required|string|max:255',
            'quiz_type' => 'required|in:manual_mcq,pdf_mcq',
            'grade' => 'required|in:Grade 1,Grade 2,Grade 3,Grade 4,Grade 5,Grade 6,Grade 7,Grade 8,Grade 9,Grade 10,Grade 11',
        ];

        if ($request->input('quiz_type') === 'manual_mcq') {
            $rules['manual_content'] = 'required|string';
        } else {
            $rules['pdf_file'] = 'required|file|mimes:pdf|max:10240';
        }

        $validated = $request->validate($rules);

        $quizData = [
            'teacher_id' => $validated['teacher_id'],
            'subject_id' => $validated['subject_id'],
            'title' => $validated['title'],
            'quiz_type' => $validated['quiz_type'],
            'grade' => $validated['grade'],
            'tenant_id' => $this->getActiveTenantId(),
        ];

        if ($validated['quiz_type'] === 'manual_mcq') {
            // Validate JSON MCQ structure
            $questions = json_decode($validated['manual_content'], true);
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($questions) || empty($questions)) {
                return back()->withErrors(['manual_content' => 'Invalid MCQ quiz questions structure.'])->withInput();
            }

            foreach ($questions as $index => $q) {
                if (empty($q['question']) || !isset($q['options']) || !is_array($q['options']) || count($q['options']) < 2) {
                    return back()->withErrors(['manual_content' => "Question " . ($index + 1) . " must contain a question text and at least 2 options."])->withInput();
                }
                if (!isset($q['correct_option']) || $q['correct_option'] < 0 || $q['correct_option'] >= count($q['options'])) {
                    return back()->withErrors(['manual_content' => "Question " . ($index + 1) . " must have a valid correct option selected."])->withInput();
                }
            }

            $quizData['manual_content'] = $validated['manual_content'];
            $quizData['pdf_file_path'] = null;
        } else {
            if ($request->hasFile('pdf_file') && $request->file('pdf_file')->isValid()) {
                $path = $request->file('pdf_file')->store('quizzes', 'public');
                $quizData['pdf_file_path'] = $path;
                
                // Parse PDF content automatically and map to manual MCQ questions!
                try {
                    $pdfParser = new PdfParser();
                    $pdf = $pdfParser->parseFile($request->file('pdf_file')->path());
                    $text = $pdf->getText();
                    
                    $questions = $this->parseMcqsFromText($text);
                    
                    if (empty($questions)) {
                        // Fallback placeholder if PDF text could not be structured automatically
                        $cleanTitle = trim($validated['title']);
                        $questions = [
                            [
                                'question' => "Which of the following is most relevant to the content of '{$cleanTitle}'?",
                                'options' => [
                                    "Primary core reference text",
                                    "Supplementary handbook guide",
                                    "Unrelated curriculum modules",
                                    "Standard diagnostic test papers"
                                ],
                                'correct_option' => 0
                            ]
                        ];
                    }
                    
                    $quizData['manual_content'] = json_encode($questions);
                    // Automatically convert/save the quiz as manual_mcq so it has fully dynamic interactive features!
                    $quizData['quiz_type'] = 'manual_mcq';
                } catch (\Exception $e) {
                    $cleanTitle = trim($validated['title']);
                    $questions = [
                        [
                            'question' => "Which of the following is most relevant to the content of '{$cleanTitle}'?",
                            'options' => [
                                "Primary core reference text",
                                "Supplementary handbook guide",
                                "Unrelated curriculum modules",
                                "Standard diagnostic test papers"
                            ],
                            'correct_option' => 0
                        ]
                    ];
                    $quizData['manual_content'] = json_encode($questions);
                    $quizData['quiz_type'] = 'manual_mcq';
                }
            } else {
                return back()->withErrors(['pdf_file' => 'Failed to upload PDF file.'])->withInput();
            }
        }

        Quiz::create($quizData);

        return redirect()->route('quizzes.index')->with('success', 'MCQ Quiz published successfully.');
    }

    /**
     * Parse MCQ questions and options from PDF plain text.
     */
    private function parseMcqsFromText($text)
    {
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        $lines = explode("\n", $text);
        
        $questions = [];
        $currentQuestion = null;
        $currentOptions = [];
        $correctOption = 0;
        
        // Regex patterns
        $questionRegex = '/^(?:Question\s+|Q)?\d+[\.\)\:\s]\s*(.+)$/i';
        $optionRegex = '/^\(?\s*([a-d])\s*[\.\)\:\s]\s*(.+)$/i';
        $answerRegex = '/^(?:Answer|Correct|Ans|Key)[\s\:\-]*([a-d])/i';
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            // Check if it matches an answer pattern
            if (preg_match($answerRegex, $line, $matches)) {
                $ansLetter = strtoupper($matches[1]);
                $ansIndex = ord($ansLetter) - ord('A');
                if ($ansIndex >= 0 && $ansIndex < 4) {
                    $correctOption = $ansIndex;
                }
                continue;
            }
            
            // Check if it matches a new question pattern
            if (preg_match($questionRegex, $line, $matches)) {
                // Save previous question
                if ($currentQuestion !== null && !empty($currentOptions)) {
                    while (count($currentOptions) < 4) {
                        $currentOptions[] = "Option " . chr(65 + count($currentOptions));
                    }
                    $questions[] = [
                        'question' => trim($currentQuestion),
                        'options' => array_map('trim', array_slice($currentOptions, 0, 4)),
                        'correct_option' => $correctOption
                    ];
                }
                
                $currentQuestion = $matches[1];
                $currentOptions = [];
                $correctOption = 0;
                continue;
            }
            
            // Check if it matches an option pattern
            if (preg_match($optionRegex, $line, $matches)) {
                $currentOptions[] = $matches[2];
                continue;
            }
            
            // Otherwise, append text to current question description if no options collected yet
            if ($currentQuestion !== null && empty($currentOptions)) {
                $currentQuestion .= " " . $line;
            }
        }
        
        // Save the final question
        if ($currentQuestion !== null && !empty($currentOptions)) {
            while (count($currentOptions) < 4) {
                $currentOptions[] = "Option " . chr(65 + count($currentOptions));
            }
            $questions[] = [
                'question' => trim($currentQuestion),
                'options' => array_map('trim', array_slice($currentOptions, 0, 4)),
                'correct_option' => $correctOption
            ];
        }
        
        return $questions;
    }
}
