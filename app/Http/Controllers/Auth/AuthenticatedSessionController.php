<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show the resident login form.
     */
    public function create(): View
    {
        return view('auth.login'); // Default resident login form
    }

    /**
     * Show the collector login form.
     */
    public function showCollectorLogin(): View
    {
        return view('auth.collector-login'); // Collector-specific login form
    }

    /**
     * Handle resident login with email verification check.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');

        // Validate credentials
        if (!Auth::validate($credentials)) {
            return back()->withErrors(['email' => 'The provided credentials are incorrect.']);
        }

        $user = User::where('email', $request->email)->first();

        // Must be a resident
        if (!$user || $user->role !== 'resident') {
            return back()->withErrors(['email' => 'You are not authorized to log in as a resident.']);
        }

        // Login and verify email
        Auth::login($user);

        if (!$user->hasVerifiedEmail()) {
            Auth::logout();

            return redirect()->route('login')->with([
                'email_verify_alert' => true,
                'email' => $user->email,
            ]);
        }

        // Success: Regenerate session
        $request->session()->regenerate();

        return redirect()->route('resident.dashboard');
    }

    /**
     * Handle collector login with role check.
     */
    public function loginCollector(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        // Attempt login
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Ensure collector role
            if ($user->role === 'collector') {
                $request->session()->regenerate();
                return redirect()->route('collector.dashboard');
            }

            // Not a collector
            Auth::logout();
            return back()->withErrors([
                'email' => 'You are not authorized to log in as a collector.',
            ]);
        }

        // Invalid login
        return back()->withErrors([
            'email' => 'The provided credentials are incorrect.',
        ]);
    }

    /**
     * Logout user (any role using web guard).
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}