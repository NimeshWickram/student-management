<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard with overview stats.
     */
    public function index()
    {
        $tenantId = $this->getActiveTenantId();

        $stats = [
            'students' => Student::where('tenant_id', $tenantId)->count(),
            'teachers' => Teacher::where('tenant_id', $tenantId)->count(),
            'subjects' => Subject::where('tenant_id', $tenantId)->count(),
        ];

        return view('dashboard', compact('stats'));
    }

    /**
     * Change the active tenant session.
     */
    public function changeTenant(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
        ]);

        session(['active_tenant_id' => $request->input('tenant_id')]);

        return redirect()->back()->with('success', 'Campus switched successfully.');
    }
}
