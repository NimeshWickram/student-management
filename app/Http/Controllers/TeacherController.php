<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Exports\TeacherExport;
use App\Imports\TeacherImport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TeacherController extends Controller
{
    /**
     * Display a listing of teachers with optional search.
     */
    public function index(Request $request)
    {
        $tenantId = $this->getActiveTenantId();
        $query = Teacher::where('tenant_id', $tenantId);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone_number', 'LIKE', "%{$search}%")
                  ->orWhere('subject', 'LIKE', "%{$search}%")
                  ->orWhere('created_at', 'LIKE', "%{$search}%");
            });
        }

        $teachers = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('teachers.index', compact('teachers'));
    }

    /**
     * AJAX auto-suggestion search.
     */
    public function search(Request $request)
    {
        $q = trim($request->input('q', ''));
        if ($q === '') {
            return response()->json([]);
        }

        $tenantId = $this->getActiveTenantId();
        $teachers = Teacher::where('tenant_id', $tenantId)->where(function ($query) use ($q) {
            $query->where('first_name', 'LIKE', "%{$q}%")
                  ->orWhere('last_name', 'LIKE', "%{$q}%")
                  ->orWhere('email', 'LIKE', "%{$q}%")
                  ->orWhere('phone_number', 'LIKE', "%{$q}%")
                  ->orWhere('subject', 'LIKE', "%{$q}%");
        })->limit(8)->get();

        $results = [];
        foreach ($teachers as $teacher) {
            $matchedField = 'name';
            $searchValue = $teacher->first_name . ' ' . $teacher->last_name;
            $detail = $teacher->email;

            if (stripos($teacher->email, $q) !== false) {
                $matchedField = 'email';
                $searchValue = $teacher->email;
                $detail = $teacher->first_name . ' ' . $teacher->last_name;
            } elseif (stripos($teacher->phone_number, $q) !== false) {
                $matchedField = 'phone';
                $searchValue = $teacher->phone_number;
                $detail = $teacher->first_name . ' ' . $teacher->last_name;
            } elseif (stripos($teacher->subject, $q) !== false) {
                $matchedField = 'subject';
                $searchValue = $teacher->subject;
                $detail = $teacher->first_name . ' ' . $teacher->last_name . ' — ' . $teacher->email;
            }

            $results[] = [
                'id'            => $teacher->id,
                'name'          => $teacher->first_name . ' ' . $teacher->last_name,
                'detail'        => $detail,
                'matched_field' => $matchedField,
                'search_value'  => $searchValue,
            ];
        }

        return response()->json($results);
    }

    /**
     * Validate and store a new teacher.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'email'        => 'required|email|unique:teachers,email',
            'phone_number' => ['required', 'string', 'regex:/^(0[0-9]{9}|[1-9][0-9]{8})$/'],
            'subject'      => 'required|string|max:255',
            'salutation'   => 'required|string|in:Dr,Professor,Mr,Miss,Mrs',
            'gender'       => 'required|string|in:male,female',
        ]);

        $validated['tenant_id'] = $this->getActiveTenantId();

        $teacher = Teacher::create($validated);

        try {
            \Illuminate\Support\Facades\Mail::to($teacher->email)->send(new \App\Mail\WelcomeTeacherMail($teacher, 'teacher123'));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to send welcome email to teacher {$teacher->email}: " . $e->getMessage());
        }

        return redirect()
            ->route('teachers.index')
            ->with('success', 'Teacher added successfully!');
    }

    /**
     * Update the specified teacher.
     */
    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'email'        => 'required|email|unique:teachers,email,' . $teacher->id,
            'phone_number' => ['required', 'string', 'regex:/^(0[0-9]{9}|[1-9][0-9]{8})$/'],
            'subject'      => 'required|string|max:255',
            'salutation'   => 'required|string|in:Dr,Professor,Mr,Miss,Mrs',
            'gender'       => 'required|string|in:male,female',
        ]);

        $teacher->update($validated);

        return redirect()
            ->route('teachers.index')
            ->with('success', 'Teacher updated successfully!');
    }

    /**
     * Remove the specified teacher.
     */
    public function destroy(Teacher $teacher)
    {
        $teacher->delete();

        return redirect()
            ->route('teachers.index')
            ->with('success', 'Teacher deleted successfully!');
    }

    /**
     * Export teachers as CSV (Excel-compatible).
     */
    public function exportExcel(Request $request)
    {
        $export = new TeacherExport($request->input('search'), $this->getActiveTenantId());
        return $export->downloadExcel();
    }

    /**
     * Export teachers as PDF.
     */
    public function exportPdf(Request $request)
    {
        $search = $request->input('search');
        $export = new TeacherExport($search, $this->getActiveTenantId());
        $teachers = $export->getRecords();

        $pdf = Pdf::loadView('exports.teachers-pdf', compact('teachers', 'search'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('teachers_' . date('Y-m-d_His') . '.pdf');
    }

    /**
     * Import teachers from a CSV/Excel file.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
        ]);

        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension());
        if (!in_array($ext, ['csv', 'txt', 'xls', 'xlsx'])) {
            return redirect()->route('teachers.index')
                ->withErrors(['file' => 'The file must be a CSV, XLS, or XLSX file.']);
        }

        $importer = new TeacherImport($this->getActiveTenantId());
        $importer->import($file->getRealPath(), $ext);

        $imported = $importer->getImportedCount();
        $skipped = $importer->getSkippedCount();
        $errors = $importer->getErrors();

        $message = "{$imported} teacher(s) imported successfully!";
        if ($skipped > 0) {
            $message .= " {$skipped} row(s) skipped (duplicates or invalid).";
        }

        if (!empty($errors)) {
            return redirect()
                ->route('teachers.index')
                ->with('success', $message)
                ->withErrors($errors);
        }

        return redirect()
            ->route('teachers.index')
            ->with('success', $message);
    }
}
