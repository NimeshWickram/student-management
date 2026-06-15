<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        // Override parent constructor
    }

    public function show()
    {
        $teacher = Auth::guard('teacher')->user();
        return view('teacher.profile', compact('teacher'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $teacher = Auth::guard('teacher')->user();

        if (!Hash::check($request->current_password, $teacher->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $teacher->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password updated successfully!');
    }
}
