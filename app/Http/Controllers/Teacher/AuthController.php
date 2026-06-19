<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        // Override parent constructor to avoid tenant scoping on login page
    }

    public function showLoginForm()
    {
        if (Auth::guard('teacher')->check()) {
            return redirect()->route('teacher.dashboard');
        }
        return view('teacher.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::guard('teacher')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()
                ->route('teacher.dashboard')
                ->with('success', 'Welcome to your teacher dashboard!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our teacher records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('teacher')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()
            ->route('teacher.login')
            ->with('success', 'Logged out successfully.');
    }
}
