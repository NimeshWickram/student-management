<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Exports\StudentExport;
use App\Imports\StudentImport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentController extends Controller
{
    /**
     * Display a listing of students with optional search.
     */
    public function index(Request $request)
    {
        $tenantId = $this->getActiveTenantId();
        $query = Student::where('tenant_id', $tenantId);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone_number', 'LIKE', "%{$search}%")
                  ->orWhere('course', 'LIKE', "%{$search}%")
                  ->orWhere('grade', 'LIKE', "%{$search}%")
                  ->orWhere('created_at', 'LIKE', "%{$search}%");
            });
        }

        $students = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('students.index', compact('students'));
    }

    /**
     * AJAX auto-suggestion search for the dashboard.
     */
    public function search(Request $request)
    {
        $q = trim($request->input('q', ''));
        if ($q === '') {
            return response()->json([]);
        }

        $tenantId = $this->getActiveTenantId();
        $students = Student::where('tenant_id', $tenantId)->where(function ($query) use ($q) {
            $query->where('first_name', 'LIKE', "%{$q}%")
                  ->orWhere('last_name', 'LIKE', "%{$q}%")
                  ->orWhere('email', 'LIKE', "%{$q}%")
                  ->orWhere('phone_number', 'LIKE', "%{$q}%")
                  ->orWhere('course', 'LIKE', "%{$q}%")
                  ->orWhere('grade', 'LIKE', "%{$q}%")
                  ->orWhere('created_at', 'LIKE', "%{$q}%");
        })->limit(8)->get();

        $results = [];
        foreach ($students as $student) {
            $matchedField = 'name';
            $searchValue = $student->first_name . ' ' . $student->last_name;
            $detail = $student->email;

            $qLower = strtolower($q);

            // Determine which field was matched for the badge label
            if (stripos($student->email, $q) !== false) {
                $matchedField = 'email';
                $searchValue = $student->email;
                $detail = $student->first_name . ' ' . $student->last_name;
            } elseif (stripos($student->phone_number, $q) !== false) {
                $matchedField = 'phone';
                $searchValue = $student->phone_number;
                $detail = $student->first_name . ' ' . $student->last_name;
            } elseif (stripos($student->course, $q) !== false) {
                $matchedField = 'course';
                $searchValue = $student->course;
                $detail = $student->first_name . ' ' . $student->last_name . ' — ' . $student->email;
            } elseif (stripos($student->created_at->format('d M Y'), $q) !== false || stripos($student->created_at->format('Y-m-d'), $q) !== false) {
                $matchedField = 'date';
                $searchValue = $student->created_at->format('d M Y');
                $detail = $student->first_name . ' ' . $student->last_name;
            }

            $results[] = [
                'id'            => $student->id,
                'name'          => $student->first_name . ' ' . $student->last_name,
                'detail'        => $detail,
                'matched_field' => $matchedField,
                'search_value'  => $searchValue,
            ];
        }

        return response()->json($results);
    }

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
            'phone_number' => ['required', 'string', 'regex:/^(0[0-9]{9}|[1-9][0-9]{8})$/'],
            'course'       => 'required|string|max:255',
            'grade'        => 'required|string|max:255',
            'password'     => 'nullable|string|min:6',
        ]);

        $rawPassword = !empty($validated['password']) ? $validated['password'] : 'student123';
        if ($rawPassword === 'teacher123') {
            $rawPassword = 'student123';
        }

        $validated['tenant_id'] = $this->getActiveTenantId();

        if ($rawPassword !== 'student123') {
            $validated['password'] = bcrypt($rawPassword);
        } else {
            unset($validated['password']);
        }

        $student = Student::create($validated);

        try {
            \Illuminate\Support\Facades\Mail::to($student->email)->send(new \App\Mail\WelcomeStudentMail($student, $rawPassword));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to send welcome email to student {$student->email}: " . $e->getMessage());
        }

        return redirect()
            ->route('students.index')
            ->with('success', 'Student registered successfully!');
    }

    /**
     * Show the form for editing a student.
     */
    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified student in the database.
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'email'        => 'required|email|unique:students,email,' . $student->id,
            'phone_number' => ['required', 'string', 'regex:/^(0[0-9]{9}|[1-9][0-9]{8})$/'],
            'course'       => 'required|string|max:255',
            'grade'        => 'required|string|max:255',
            'password'     => 'nullable|string|min:6',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $student->update($validated);

        return redirect()
            ->route('students.index')
            ->with('success', 'Student updated successfully!');
    }

    /**
     * Remove the specified student from the database.
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()
            ->route('students.index')
            ->with('success', 'Student deleted successfully!');
    }

    /**
     * Export students as CSV (Excel-compatible).
     */
    public function exportExcel(Request $request)
    {
        $export = new StudentExport($request->input('search'), $this->getActiveTenantId());
        return $export->downloadExcel();
    }

    /**
     * Export students as PDF.
     */
    public function exportPdf(Request $request)
    {
        $search = $request->input('search');
        $export = new StudentExport($search, $this->getActiveTenantId());
        $students = $export->getRecords();

        $pdf = Pdf::loadView('exports.students-pdf', compact('students', 'search'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('students_' . date('Y-m-d_His') . '.pdf');
    }

    /**
     * Import students from a CSV/Excel file.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
        ]);

        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension());
        if (!in_array($ext, ['csv', 'txt', 'xls', 'xlsx'])) {
            return redirect()->route('students.index')
                ->withErrors(['file' => 'The file must be a CSV, XLS, or XLSX file.']);
        }

        $importer = new StudentImport($this->getActiveTenantId());
        $importer->import($file->getRealPath(), $ext);

        $imported = $importer->getImportedCount();
        $skipped = $importer->getSkippedCount();
        $errors = $importer->getErrors();

        $message = "{$imported} student(s) imported successfully!";
        if ($skipped > 0) {
            $message .= " {$skipped} row(s) skipped (duplicates or invalid).";
        }

        if (!empty($errors)) {
            return redirect()
                ->route('students.index')
                ->with('success', $message)
                ->withErrors($errors);
        }

        return redirect()
            ->route('students.index')
            ->with('success', $message);
    }
}
