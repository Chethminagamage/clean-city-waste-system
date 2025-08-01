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
        return view('auth.collector-login');
    }

    public function store(LoginRequest $request): RedirectResponse
{
    $credentials = $request->only('email', 'password');

    if (!Auth::validate($credentials)) {
        return back()->withErrors(['email' => 'The provided credentials are incorrect.']);
    }

    $user = User::where('email', $request->email)->first();

    if (!$user || $user->role !== 'resident') {
        return back()->withErrors(['email' => 'You are not authorized to log in as a resident.']);
    }

    // ✅ Blocked user check
    if ($user->status === 'blocked') {
        return back()->withErrors(['email' => 'Your account has been blocked by the admin.']);
    }

    if (!$user->hasVerifiedEmail()) {
        return redirect()->route('login')->with([
            'email_verify_alert' => true,
            'email' => $user->email,
        ]);
    }

    // ✅ If 2FA is enabled, send OTP and trigger modal
    if ($user->two_factor_enabled) {
        $otp = rand(100000, 999999);

        // Store OTP and user ID in session
        session([
            '2fa:user:id' => $user->id,
            '2fa:otp' => $otp
        ]);

        // Logout to prevent bypass
        Auth::logout();

        // Send OTP via email
        Mail::to($user->email)->send(new OTPVerificationMail($otp));

        // Redirect back to login with modal flag
        return redirect()->route('login')->with('show_2fa_modal', true);
    }

    // ✅ Regular login
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

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->role === 'collector') {
                $request->session()->regenerate();
                return redirect()->route('collector.dashboard');
            }

            Auth::logout();
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
}