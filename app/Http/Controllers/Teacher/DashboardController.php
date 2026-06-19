<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizSubmission;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        // Override parent constructor to avoid tenant scoping issues
    }

    public function index()
    {
        $teacher = Auth::guard('teacher')->user();
        $teacherId = $teacher->id;
        $tenantId = $teacher->tenant_id;

        // Teacher's quizzes
        $quizIds = Quiz::where('teacher_id', $teacherId)->pluck('id');
        $totalQuizzes = $quizIds->count();

        // Submissions for teacher's quizzes
        $totalSubmissions = QuizSubmission::whereIn('quiz_id', $quizIds)->count();
        $averageScore = QuizSubmission::whereIn('quiz_id', $quizIds)->avg('score');
        $averageScore = $averageScore !== null ? round($averageScore) : 0;

        // Unique students who attempted
        $uniqueStudents = QuizSubmission::whereIn('quiz_id', $quizIds)
            ->distinct('student_id')->count('student_id');

        // Recent quizzes with submission counts
        $recentQuizzes = Quiz::where('teacher_id', $teacherId)
            ->with('subject')
            ->withCount('submissions')
            ->latest()
            ->limit(5)
            ->get();

        // Recent submissions
        $recentSubmissions = QuizSubmission::whereIn('quiz_id', $quizIds)
            ->with(['student', 'quiz.subject'])
            ->latest()
            ->limit(10)
            ->get();

        $stats = [
            'total_quizzes' => $totalQuizzes,
            'total_submissions' => $totalSubmissions,
            'average_score' => $averageScore,
            'unique_students' => $uniqueStudents,
        ];

        return view('teacher.dashboard', compact('teacher', 'stats', 'recentQuizzes', 'recentSubmissions'));
    }
}
