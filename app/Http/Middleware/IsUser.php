<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsUser
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated as a regular user
        if (Auth::guard('user')->check()) {
            return $next($request); // Allow access
        }

        // Redirect to user login page if not authenticated
        return redirect()->route('login')->with('error', 'Access Denied! You are not a user.');
    }
}