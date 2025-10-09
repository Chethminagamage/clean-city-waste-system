<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;

class LoginController extends Controller
{

    protected $authService;

    public function __construct(AuthService $authService) {
        $this->authService = $authService;
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
        // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

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