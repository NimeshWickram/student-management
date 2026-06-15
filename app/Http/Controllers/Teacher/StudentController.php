<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Quiz;
use App\Models\QuizSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function __construct()
    {
        // Override parent constructor
    }

    /**
     * List students in the same tenant with their quiz stats.
     */
    public function index(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();
        $tenantId = $teacher->tenant_id;

        $query = Student::where('tenant_id', $tenantId);

        if ($request->filled('grade')) {
            $query->where('grade', $request->input('grade'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $students = $query->withCount('submissions')
            ->orderBy('first_name')
            ->paginate(15)
            ->withQueryString();

        // Get available grades for filter
        $grades = Student::where('tenant_id', $tenantId)
            ->distinct()->pluck('grade')->filter()->sort()->values();

        return view('teacher.students.index', compact('students', 'grades'));
    }

    /**
     * Show a specific student's quiz history.
     */
    public function show($studentId)
    {
        $teacher = Auth::guard('teacher')->user();
        $student = Student::where('tenant_id', $teacher->tenant_id)
            ->findOrFail($studentId);

        $submissions = QuizSubmission::where('student_id', $student->id)
            ->with(['quiz.subject', 'quiz.teacher'])
            ->latest()
            ->get();

        $totalQuizzes = $submissions->unique('quiz_id')->count();
        $averageScore = $submissions->count() > 0 ? round($submissions->avg('score')) : 0;
        $highestScore = $submissions->count() > 0 ? $submissions->max('score') : 0;

        return view('teacher.students.show', compact(
            'student', 'submissions', 'totalQuizzes', 'averageScore', 'highestScore'
        ));
    }
}
