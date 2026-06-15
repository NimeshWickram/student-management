<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            // Check which guard is being used
            if ($request->is('teacher/*')) {
                return route('teacher.login');
            }
            if ($request->is('student/*')) {
                return route('student.login');
            }
            return route('admin.login');
        }
        return null;
    }
}
