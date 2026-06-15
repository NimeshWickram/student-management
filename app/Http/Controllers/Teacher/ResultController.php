<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResultController extends Controller
{
    public function __construct()
    {
        // Override parent constructor
    }

    /**
     * List all submissions across teacher's quizzes.
     */
    public function index(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();
        $quizIds = Quiz::where('teacher_id', $teacher->id)->pluck('id');

        $query = QuizSubmission::whereIn('quiz_id', $quizIds)
            ->with(['student', 'quiz.subject']);

        // Filter by quiz
        if ($request->filled('quiz_id')) {
            $query->where('quiz_id', $request->input('quiz_id'));
        }

        // Filter by grade (through quiz)
        if ($request->filled('grade')) {
            $grade = $request->input('grade');
            $query->whereHas('quiz', function ($q) use ($grade) {
                $q->where('grade', $grade);
            });
        }

        // Search by student name
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%");
            });
        }

        // Filter by score range
        if ($request->filled('score_range')) {
            $range = $request->input('score_range');
            if ($range === 'below_35') {
                $query->where('score', '<', 35);
            } elseif ($range === 'above_35') {
                $query->where('score', '>=', 35);
            } elseif ($range === 'above_50') {
                $query->where('score', '>=', 50);
            } elseif ($range === 'above_65') {
                $query->where('score', '>=', 65);
            } elseif ($range === 'above_75') {
                $query->where('score', '>=', 75);
            }
        }

        // Sort
        $sortBy = $request->input('sort', 'created_at');
        $sortDir = $request->input('dir', 'desc');
        if (in_array($sortBy, ['score', 'created_at'])) {
            $query->orderBy($sortBy, $sortDir);
        }

        $submissions = $query->paginate(15)->withQueryString();

        // For filter dropdowns
        $quizzes = Quiz::where('teacher_id', $teacher->id)
            ->orderBy('title')
            ->get(['id', 'title', 'grade']);

        $grades = Quiz::where('teacher_id', $teacher->id)
            ->distinct()->pluck('grade')->filter()->sort()->values();

        return view('teacher.results.index', compact('submissions', 'quizzes', 'grades'));
    }

    /**
     * Show detailed view of a single submission.
     */
    public function show($submissionId)
    {
        $teacher = Auth::guard('teacher')->user();
        $submission = QuizSubmission::with(['student', 'quiz.subject'])->findOrFail($submissionId);

        // Security: ensure this submission belongs to one of the teacher's quizzes
        if ($submission->quiz->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized access to this submission.');
        }

        $questions = json_decode($submission->quiz->manual_content, true);

        return view('teacher.results.show', compact('submission', 'questions'));
    }

    /**
     * Show all submissions and analytics for a specific quiz.
     */
    public function quizResults(Request $request, $quizId)
    {
        $teacher = Auth::guard('teacher')->user();
        $quiz = Quiz::where('teacher_id', $teacher->id)
            ->with('subject')
            ->findOrFail($quizId);

        // Fetch all submissions for global analytics
        $allSubmissions = QuizSubmission::where('quiz_id', $quiz->id)
            ->with('student')
            ->orderBy('score', 'desc')
            ->get();

        // Build query for student rank table (filterable)
        $query = QuizSubmission::where('quiz_id', $quiz->id)
            ->with('student');

        // Filter by score range
        if ($request->filled('score_range')) {
            $range = $request->input('score_range');
            if ($range === 'below_35') {
                $query->where('score', '<', 35);
            } elseif ($range === 'above_35') {
                $query->where('score', '>=', 35);
            } elseif ($range === 'above_50') {
                $query->where('score', '>=', 50);
            } elseif ($range === 'above_65') {
                $query->where('score', '>=', 65);
            } elseif ($range === 'above_75') {
                $query->where('score', '>=', 75);
            }
        }

        // Search by student name
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%");
            });
        }

        $submissions = $query->orderBy('score', 'desc')->get();

        $questions = json_decode($quiz->manual_content, true) ?? [];

        // Analytics computed using all attempts
        $totalSubmissions = $allSubmissions->count();
        $averageScore = $totalSubmissions > 0 ? round($allSubmissions->avg('score')) : 0;
        $highestScore = $totalSubmissions > 0 ? $allSubmissions->max('score') : 0;
        $lowestScore = $totalSubmissions > 0 ? $allSubmissions->min('score') : 0;

        // Per-question accuracy computed using all attempts
        $questionStats = [];
        foreach ($questions as $qIndex => $q) {
            $correctCount = 0;
            $attemptCount = 0;
            foreach ($allSubmissions as $sub) {
                $answers = is_array($sub->answers) ? $sub->answers : [];
                foreach ($answers as $a) {
                    if (isset($a['question_index']) && $a['question_index'] == $qIndex) {
                        $attemptCount++;
                        if (isset($a['selected_option']) && $a['selected_option'] == $a['correct_option']) {
                            $correctCount++;
                        }
                    }
                }
            }
            $questionStats[] = [
                'question' => $q['question'] ?? 'Question ' . ($qIndex + 1),
                'attempts' => $attemptCount,
                'correct' => $correctCount,
                'accuracy' => $attemptCount > 0 ? round(($correctCount / $attemptCount) * 100) : 0,
            ];
        }

        // Score distribution computed using all attempts
        $scoreDistribution = [
            '0-20' => $allSubmissions->where('score', '>=', 0)->where('score', '<=', 20)->count(),
            '21-40' => $allSubmissions->where('score', '>', 20)->where('score', '<=', 40)->count(),
            '41-60' => $allSubmissions->where('score', '>', 40)->where('score', '<=', 60)->count(),
            '61-80' => $allSubmissions->where('score', '>', 60)->where('score', '<=', 80)->count(),
            '81-100' => $allSubmissions->where('score', '>', 80)->where('score', '<=', 100)->count(),
        ];

        return view('teacher.results.quiz', compact(
            'quiz', 'submissions', 'questions', 'questionStats',
            'totalSubmissions', 'averageScore', 'highestScore', 'lowestScore',
            'scoreDistribution'
        ));
    }

    /**
     * Export submissions list as Excel.
     */
    public function exportExcel(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();
        $filters = [
            'quiz_id' => $request->input('quiz_id'),
            'grade' => $request->input('grade'),
            'search' => $request->input('search'),
            'score_range' => $request->input('score_range'),
        ];

        $export = new \App\Exports\ResultExport($teacher->id, $filters);
        return $export->downloadExcel();
    }

    /**
     * Export submissions list as PDF.
     */
    public function exportPdf(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();
        $filters = [
            'quiz_id' => $request->input('quiz_id'),
            'grade' => $request->input('grade'),
            'search' => $request->input('search'),
            'score_range' => $request->input('score_range'),
        ];

        $export = new \App\Exports\ResultExport($teacher->id, $filters);
        $submissions = $export->getRecords();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.results-pdf', compact('submissions', 'filters'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('quiz_results_' . date('Y-m-d_His') . '.pdf');
    }
}
