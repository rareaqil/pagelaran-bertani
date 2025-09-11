<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated as an admin
        if (Auth::guard('admin')->check()) {
            return $next($request); // Allow access
        }

        // Redirect to admin login page if not authenticated
        return redirect()->route('admin.login')->with('error', 'Access Denied! You are not an admin.');
    }
}
