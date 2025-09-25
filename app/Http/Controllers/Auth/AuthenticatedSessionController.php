<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use App\Services\CaptchaService;
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
    protected $authService;
    protected $captchaService;

    public function __construct(AuthService $authService, CaptchaService $captchaService)
    {
        $this->authService = $authService;
        $this->captchaService = $captchaService;
    }
    
    public function create(): View
    {
        // No need to generate anything for reCAPTCHA
        return view('auth.login');
    }

    public function showCollectorLogin(): View
    {
        return view('auth.collector_login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        // Validate reCAPTCHA first
        if (!$this->captchaService->verifyRecaptcha($request->input('g-recaptcha-response'))) {
            return back()->withErrors(['captcha' => 'Please complete the reCAPTCHA verification.'])
                         ->withInput($request->only('email'));
        }

        $user = User::where('email', $request->email)->first();

        // Use AuthService to validate login attempt
        $validationResult = $this->authService->validateResidentLogin($user, $request->password);

        if (!$validationResult['success']) {
            if ($validationResult['error'] === 'email_verification') {
                return redirect()->route('login')->with([
                    'email_verify_alert' => true,
                    'email' => $validationResult['user']->email,
                ]);
            }

            return back()->withErrors([$validationResult['error'] => $validationResult['message']])
                         ->withInput($request->only('email'));
        }

        $user = $validationResult['user'];

        // Two-factor authentication (OTP)
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

        // Handle successful login
        $this->authService->handleSuccessfulLogin($user);

        // Final login
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('resident.dashboard');
    }

    public function loginCollector(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'g-recaptcha-response' => ['required', new \App\Rules\RecaptchaRule()],
        ]);

        $user = User::where('email', $request->email)->first();

        // Use AuthService to validate collector login attempt
        $validationResult = $this->authService->validateCollectorLogin($user, $request->password);

        if (!$validationResult['success']) {
            return back()->withErrors([$validationResult['error'] => $validationResult['message']]);
        }

        $user = $validationResult['user'];

        // Handle successful login
        $this->authService->handleSuccessfulLogin($user);

        // All good — log in with guard
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