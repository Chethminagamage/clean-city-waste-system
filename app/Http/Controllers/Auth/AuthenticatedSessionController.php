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
use Illuminate\Support\Facades\Hash;

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
    $user = User::where('email', $request->email)->first();

    // 1. Email not found
    if (!$user) {
        return back()->withErrors(['email' => 'No account found with this email address.']);
    }

    // 2. Incorrect password
    if (!Hash::check($request->password, $user->password)) {
        return back()->withErrors(['email' => 'Incorrect username or password.']);
    }

    // 3. Role check
    if ($user->role !== 'resident') {
        return back()->withErrors(['email' => 'You are not authorized to log in as a resident.']);
    }

    // 4. Blocked account check
    if ($user->status === 'blocked') {
        return back()->withErrors(['email' => 'Your account has been blocked by the admin.']);
    }

    // 5. Email verification check
    if (!$user->hasVerifiedEmail()) {
        return redirect()->route('login')->with([
            'email_verify_alert' => true,
            'email' => $user->email,
        ]);
    }

    // 6. Two-factor authentication (OTP)
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

    // 7. Final login
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

    $user = User::where('email', $request->email)->first();

    // 1. Email not found
    if (!$user) {
        return back()->withErrors(['email' => 'No account found with this email address.']);
    }

    // 2. Role check BEFORE login
    if ($user->role !== 'collector') {
        return back()->withErrors(['email' => 'You are not authorized to log in as a collector.']);
    }

    // 3. Password check
    if (!Hash::check($request->password, $user->password)) {
        return back()->withErrors(['email' => 'Incorrect password.']);
    }

    // 4. Account block check
    if ($user->status === 'blocked') {
        return back()->withErrors(['email' => 'Your account is blocked. Please contact admin.']);
    }

    // 5. All good — log in with guard
    Auth::guard('collector')->login($user);
    $request->session()->regenerate();

    return redirect()->route('collector.dashboard');
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