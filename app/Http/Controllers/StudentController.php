<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Show the student registration form.
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Validate and store a new student in the database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'email'        => 'required|email|unique:students,email',
            'phone_number' => 'required|string|max:20',
            'course'       => 'required|string|max:255',
        ]);

        Student::create($validated);

        return redirect()
            ->route('students.create')
            ->with('success', 'Student registered successfully!');
    }
}
