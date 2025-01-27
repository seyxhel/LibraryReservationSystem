<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated and has the role 'admin'
        if (Auth::guard('admin')->check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // Redirect or abort if the user is not an admin
        return redirect('/')->with('error', 'Unauthorized access.');
    }
}
