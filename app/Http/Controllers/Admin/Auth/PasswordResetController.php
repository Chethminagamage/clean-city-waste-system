<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\Rules;
use App\Models\Admin;
use Illuminate\Support\Str;
use App\Services\ActivityLogService;

class PasswordResetController extends Controller
{
    protected $activityLogger;

    public function __construct(ActivityLogService $activityLogger)
    {
        $this->activityLogger = $activityLogger;
    }

    /**
     * Show the forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('admin.auth.forgot-password');
    }

    /**
     * Handle forgot password request
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:admins,email'],
            'g-recaptcha-response' => ['required'],
        ], [
            'email.exists' => 'We can\'t find an admin with that email address.',
            'g-recaptcha-response.required' => 'Please complete the reCAPTCHA verification.'
        ]);

        // Verify reCAPTCHA
        $recaptchaResponse = $request->input('g-recaptcha-response');
        $recaptchaSecret = config('services.recaptcha.secret_key');
        
        if ($recaptchaSecret) {
            $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptchaSecret}&response={$recaptchaResponse}");
            $responseData = json_decode($response);
            
            if (!$responseData->success) {
                return back()->withErrors(['g-recaptcha-response' => 'reCAPTCHA verification failed. Please try again.']);
            }
        }

        // Check if admin account is active
        $admin = Admin::where('email', $request->email)->first();
        if (!$admin->is_active) {
            return back()->withErrors(['email' => 'This admin account is currently disabled.']);
        }

        // Check if admin account is locked
        if ($admin->locked_until && $admin->locked_until > now()) {
            return back()->withErrors(['email' => 'This admin account is temporarily locked. Please try again later.']);
        }

        // Check for recent password reset requests from this IP
        $recentRequests = \App\Models\ActivityLog::where('ip_address', $request->ip())
            ->where('action', 'password_reset_requested')
            ->where('created_at', '>=', now()->subMinutes(15))
            ->count();
            
        if ($recentRequests >= 3) {
            return back()->withErrors(['email' => 'Too many password reset requests from this IP address. Please try again later.']);
        }

        // Log the password reset request
        $this->activityLogger->log([
            'user_id' => $admin->id,
            'user_type' => 'admin',
            'action' => 'password_reset_requested',
            'description' => 'Admin requested password reset',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'risk_level' => 'medium'
        ]);

        // Send the password reset notification
        $status = Password::broker('admins')->sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Password reset link sent! Check your email.');
        }

        return back()->withErrors(['email' => __($status)]);
    }

    /**
     * Show the reset password form
     */
    public function showResetPasswordForm(Request $request, $token)
    {
        return view('admin.auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Handle password reset
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:admins,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::broker('admins')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($admin, $password) use ($request) {
                $admin->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                // Reset failed login attempts and unlock account
                $admin->failed_login_attempts = 0;
                $admin->locked_until = null;
                
                $admin->save();

                // Log successful password reset
                $this->activityLogger->log([
                    'user_id' => $admin->id,
                    'user_type' => 'admin',
                    'action' => 'password_reset_completed',
                    'description' => 'Admin successfully reset password',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'risk_level' => 'high'
                ]);

                event(new PasswordReset($admin));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('admin.login')->with('success', 'Your password has been reset successfully! You can now login.');
        }

        return back()->withErrors(['email' => [__($status)]]);
    }
}