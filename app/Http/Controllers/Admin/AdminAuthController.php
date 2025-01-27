<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminAuthController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function showLoginForm()
    {
        return view('auth.admin-login'); // Refers to resources/views/auth/admin-login.blade.php
    }

    /**
     * Handle the admin login request.
     */
    public function login(Request $request)
    {
        // Validate login credentials
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check the credentials
        $credentials = [
            'Email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::guard('admin')->attempt($credentials)) {
            // Log in the admin and redirect to dashboard
            return redirect()->route('admin.dashboard')->with('success', 'Logged in successfully!');
        }

        // If credentials are incorrect, redirect back with an error message
        return redirect()->back()->withErrors(['email' => 'Invalid credentials.']);
    }

    /**
     * Log out the admin.
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate the session token
        $request->session()->regenerateToken();

        // Redirect to the admin login page
        return redirect()->route('admin.login');
    }
}
