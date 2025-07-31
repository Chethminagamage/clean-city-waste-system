<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class LoginController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function showLoginForm()
    {
        return view('admin.auth.login'); // ✅ View: resources/views/admin/auth/login.blade.php
    }

    /**
     * Handle the admin login request.
     */
    public function login(Request $request)
    {
        // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Find admin by email
        $admin = Admin::where('email', $request->email)->first();

        // Check credentials
        if ($admin && Hash::check($request->password, $admin->password)) {
            // Log in with admin guard
            Auth::guard('admin')->login($admin);

            // Regenerate session to prevent fixation
            $request->session()->regenerate();

            // Redirect to dashboard
            return redirect()->route('admin.dashboard');
        }

        // Invalid credentials
        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->withInput($request->only('email'));
    }

    /**
     * Logout the admin and redirect to login form.
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        // Invalidate and regenerate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}