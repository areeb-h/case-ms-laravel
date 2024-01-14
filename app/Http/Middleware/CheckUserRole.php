<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Check if the authenticated user has the specified role
        if (Auth::user()->hasRole($role)) {
            return $next($request);
        }

        // Redirect or return a response if the user doesn't have the role
         // Redirect back with an error message
         return back()->withInput()->with('error', 'You do not have permission to access this page.');
    }
}
