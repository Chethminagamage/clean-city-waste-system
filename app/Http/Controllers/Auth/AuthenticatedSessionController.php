<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use App\Models\User;
use App\Mail\OTPVerificationMail;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function showCollectorLogin(): View
    {
        return view('auth.collector_login');
    }

    public function store(LoginRequest $request): RedirectResponse
{
    $credentials = $request->only('email', 'password');

    // First check if the credentials are correct
    if (!Auth::validate($credentials)) {
        return back()->withErrors(['email' => 'The provided credentials are incorrect.']);
    }

    // Get the user after validation
    $user = User::where('email', $request->email)->first();

    // Check if user exists and is a resident
    if (!$user || $user->role !== 'resident') {
        return back()->withErrors(['email' => 'You are not authorized to log in as a resident.']);
    }

    // Blocked account check
    if ($user->status === 'blocked' || $user->status == 0) {
        return back()->withErrors(['email' => 'Your account has been blocked by the admin.']);
    }

    // Email verification check
    if (!$user->hasVerifiedEmail()) {
        return redirect()->route('login')->with([
            'email_verify_alert' => true,
            'email' => $user->email,
        ]);
    }

    // 2FA check
    if ($user->two_factor_enabled) {
        $otp = rand(100000, 999999);

        session([
            '2fa:user:id' => $user->id,
            '2fa:otp' => $otp,
        ]);

        Auth::logout();

        Mail::to($user->email)->send(new OTPVerificationMail($otp));

        return redirect()->route('login')->with('show_2fa_modal', true);
    }

    // Regular login
    Auth::login($user);
    $request->session()->regenerate();

    return redirect()->route('resident.dashboard');
}

    public function loginCollector(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        // Use collector guard
        if (Auth::guard('collector')->attempt($credentials)) {
            $user = Auth::guard('collector')->user();

            if ($user->role === 'collector') {
                $request->session()->regenerate();
                return redirect()->route('collector.dashboard');
            }

            // If role doesn't match, logout from collector guard
            Auth::guard('collector')->logout();
            return back()->withErrors([
                'email' => 'You are not authorized to log in as a collector.',
            ]);
        }

        return back()->withErrors([
            'email' => 'The provided credentials are incorrect.',
        ]);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function logoutCollector(Request $request): RedirectResponse
    {
        Auth::guard('collector')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('collector.login');
    }
}