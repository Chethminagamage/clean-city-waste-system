<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\Services\CaptchaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;

class LoginController extends Controller
{

    protected $authService;
    protected $captchaService;

    public function __construct(
        AuthService $authService,
        CaptchaService $captchaService
    ) {
        $this->authService = $authService;
        $this->captchaService = $captchaService;
    }
    /**
     * Show the admin login form.
     */
    public function showLoginForm()
    {
        return view('admin.auth.login'); 
    }

    /**
     * Handle the admin login request with enhanced security.
     */
    public function login(Request $request)
    {
        // Validate input including reCAPTCHA
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'g-recaptcha-response' => 'required',
        ]);

        // Verify reCAPTCHA
        if (!$this->captchaService->verifyRecaptcha($request->input('g-recaptcha-response'))) {
            return back()->with('error', 'Please complete the reCAPTCHA verification');
        }

        // Find admin by email
        $admin = Admin::where('email', $request->email)->first();

        // Use AuthService to validate admin login attempt
        $validationResult = $this->authService->validateAdminLogin($admin, $request->password);

        if (!$validationResult['success']) {
            return back()->withErrors([$validationResult['error'] => $validationResult['message']])
                        ->withInput($request->only('email'));
        }

        $admin = $validationResult['admin'];

        // Handle successful login
        $this->authService->handleSuccessfulLogin($admin);

        // Check if 2FA is enabled
        if ($admin->hasTwoFactorEnabled()) {
            session(['2fa_admin_id' => $admin->id]);
            return redirect()->route('admin.2fa.verify');
        }

        // Log in with admin guard
        Auth::guard('admin')->login($admin);

        // Regenerate session to prevent fixation
        $request->session()->regenerate();

        // Update login timestamp
        $admin->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        // Redirect to dashboard
        return redirect()->route('admin.dashboard.main')
            ->with('success', 'Welcome back, ' . $admin->name . '!');
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