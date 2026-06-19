<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Exports\SubjectExport;
use App\Imports\SubjectImport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class SubjectController extends Controller
{
    /**
     * Display a listing of subjects with optional search.
     */
    public function index(Request $request)
    {
        $tenantId = $this->getActiveTenantId();
        $query = Subject::where('tenant_id', $tenantId);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('credits', 'LIKE', "%{$search}%");
            });
        }

        $subjects = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('subjects.index', compact('subjects'));
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
        $subjects = Subject::where('tenant_id', $tenantId)->where(function ($query) use ($q) {
            $query->where('name', 'LIKE', "%{$q}%")
                  ->orWhere('code', 'LIKE', "%{$q}%")
                  ->orWhere('description', 'LIKE', "%{$q}%");
        })->limit(8)->get();

        $results = [];
        foreach ($subjects as $subject) {
            $matchedField = 'name';
            $searchValue = $subject->name;
            $detail = $subject->code . ' — ' . $subject->credits . ' credits';

            if (stripos($subject->code, $q) !== false) {
                $matchedField = 'code';
                $searchValue = $subject->code;
                $detail = $subject->name;
            } elseif (stripos($subject->description, $q) !== false) {
                $matchedField = 'description';
                $searchValue = $subject->name;
                $detail = substr($subject->description, 0, 60) . '...';
            }

            $results[] = [
                'id'            => $subject->id,
                'name'          => $subject->name,
                'detail'        => $detail,
                'matched_field' => $matchedField,
                'search_value'  => $searchValue,
            ];
        }

        return response()->json($results);
    }

    /**
     * Validate and store a new subject.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:20|unique:subjects,code',
            'description' => 'nullable|string|max:1000',
            'credits'     => 'required|integer|min:1|max:10',
        ]);

        $validated['tenant_id'] = $this->getActiveTenantId();

        Subject::create($validated);

        return redirect()
            ->route('subjects.index')
            ->with('success', 'Subject added successfully!');
    }

    /**
     * Update the specified subject.
     */
    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:20|unique:subjects,code,' . $subject->id,
            'description' => 'nullable|string|max:1000',
            'credits'     => 'required|integer|min:1|max:10',
        ]);

        $subject->update($validated);

        return redirect()
            ->route('subjects.index')
            ->with('success', 'Subject updated successfully!');
    }

    /**
     * Remove the specified subject.
     */
    public function destroy(Subject $subject)
    {
        $subject->delete();

        return redirect()
            ->route('subjects.index')
            ->with('success', 'Subject deleted successfully!');
    }

    /**
     * Export subjects as CSV (Excel-compatible).
     */
    public function exportExcel(Request $request)
    {
        $export = new SubjectExport($request->input('search'), $this->getActiveTenantId());
        return $export->downloadExcel();
    }

    /**
     * Export subjects as PDF.
     */
    public function exportPdf(Request $request)
    {
        $search = $request->input('search');
        $export = new SubjectExport($search, $this->getActiveTenantId());
        $subjects = $export->getRecords();

        $pdf = Pdf::loadView('exports.subjects-pdf', compact('subjects', 'search'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('subjects_' . date('Y-m-d_His') . '.pdf');
    }

    /**
     * Import subjects from a CSV/Excel file.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
        ]);

        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension());
        if (!in_array($ext, ['csv', 'txt', 'xls', 'xlsx'])) {
            return redirect()->route('subjects.index')
                ->withErrors(['file' => 'The file must be a CSV, XLS, or XLSX file.']);
        }

        $importer = new SubjectImport($this->getActiveTenantId());
        $importer->import($file->getRealPath(), $ext);

        $imported = $importer->getImportedCount();
        $skipped = $importer->getSkippedCount();
        $errors = $importer->getErrors();

        $message = "{$imported} subject(s) imported successfully!";
        if ($skipped > 0) {
            $message .= " {$skipped} row(s) skipped (duplicates or invalid).";
        }

        if (!empty($errors)) {
            return redirect()
                ->route('subjects.index')
                ->with('success', $message)
                ->withErrors($errors);
        }

        return redirect()
            ->route('subjects.index')
            ->with('success', $message);
    }
}
