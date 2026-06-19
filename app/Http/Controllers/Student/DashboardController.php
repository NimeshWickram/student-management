<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Subject;
use App\Models\QuizSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the student dashboard.
     */
    public function index()
    {
        $student = Auth::guard('student')->user();

        // Calculate submissions statistics
        $totalSubmissions = QuizSubmission::where('student_id', $student->id)->count();
        $averageScore = QuizSubmission::where('student_id', $student->id)->avg('score');
        $averageScore = $averageScore !== null ? round($averageScore) : 0;

        // Fetch subjects for student's tenant
        $subjects = Subject::where('tenant_id', $student->tenant_id)->orderBy('name')->get();

        // Fetch only quizzes that match the student's tenant AND grade
        $quizzes = Quiz::where('tenant_id', $student->tenant_id)
            ->where('grade', $student->grade)
            ->with(['teacher', 'subject'])
            ->latest()
            ->get();

        // Get past submissions keyed by quiz_id
        $submissions = QuizSubmission::where('student_id', $student->id)
            ->latest()
            ->get()
            ->unique('quiz_id')
            ->keyBy('quiz_id');

        $stats = [
            'quizzes_taken' => $totalSubmissions,
            'average_score' => $averageScore,
            'course' => $student->course,
        ];

        return view('student.dashboard', compact('student', 'stats', 'quizzes', 'subjects', 'submissions'));
    }

    /**
     * Display the interactive quiz taking page.
     */
    public function takeQuiz($id)
    {
        $student = Auth::guard('student')->user();
        $quiz = Quiz::where('tenant_id', $student->tenant_id)
            ->where('grade', $student->grade)
            ->with(['teacher', 'subject'])
            ->findOrFail($id);
            
        $questions = json_decode($quiz->manual_content, true);

        if (empty($questions)) {
            return redirect()
                ->route('student.dashboard')
                ->withErrors(['quiz' => 'This quiz has no dynamic questions available.']);
        }

        return view('student.quiz.take', compact('quiz', 'questions'));
    }

    /**
     * Grade the quiz submission and save the score.
     */
    public function submitQuiz(Request $request, $id)
    {
        $student = Auth::guard('student')->user();
        $quiz = Quiz::where('tenant_id', $student->tenant_id)
            ->where('grade', $student->grade)
            ->findOrFail($id);
        $questions = json_decode($quiz->manual_content, true);

        $request->validate([
            'answers' => 'required|array',
        ]);

        $studentAnswers = $request->input('answers');
        $totalQuestions = count($questions);
        $correctAnswers = 0;
        $detailedAnswers = [];

        foreach ($questions as $index => $q) {
            $correctOption = (int) $q['correct_option'];
            $studentOption = isset($studentAnswers[$index]) ? (int) $studentAnswers[$index] : null;

            if ($studentOption !== null && $studentOption === $correctOption) {
                $correctAnswers++;
            }

            $detailedAnswers[] = [
                'question_index'  => $index,
                'selected_option' => $studentOption,
                'correct_option'  => $correctOption,
            ];
        }

        $score = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100) : 0;

        $submission = QuizSubmission::create([
            'student_id'      => Auth::guard('student')->id(),
            'quiz_id'         => $quiz->id,
            'score'           => $score,
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctAnswers,
            'answers'         => $detailedAnswers,
        ]);

        return redirect()
            ->route('student.quiz.result', $submission->id)
            ->with('success', 'Quiz submitted successfully! Here are your results.');
    }

    /**
     * Display the quiz result page.
     */
    public function viewResult($id)
    {
        $submission = QuizSubmission::with(['quiz.teacher', 'quiz.subject'])->findOrFail($id);

        // Security check: Ensure this submission belongs to the logged-in student
        if ($submission->student_id !== Auth::guard('student')->id()) {
            abort(403, 'Unauthorized access to quiz results.');
        }

        $questions = json_decode($submission->quiz->manual_content, true);

        return view('student.quiz.result', compact('submission', 'questions'));
    }
}
