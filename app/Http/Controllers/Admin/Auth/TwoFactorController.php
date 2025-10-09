<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Services\AdminSecurityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TwoFactorController extends Controller
{
    protected $adminSecurityService;

    public function __construct(AdminSecurityService $adminSecurityService)
    {
        $this->adminSecurityService = $adminSecurityService;
    }

    /**
     * Show the 2FA verification form
     */
    public function showVerifyForm()
    {
        if (!session('2fa_admin_id')) {
            return redirect()->route('admin.login');
        }

        return view('admin.auth.2fa-verify');
    }

    /**
     * Verify the 2FA code and complete login
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $adminId = session('2fa_admin_id');
        if (!$adminId) {
            return redirect()->route('admin.login')->with('error', 'Session expired. Please login again.');
        }

        $admin = Admin::findOrFail($adminId);

        if ($this->adminSecurityService->validateTwoFactor($admin, $request->code)) {
            // Remove 2FA session data
            session()->forget('2fa_admin_id');

            // Complete login process
            Auth::guard('admin')->login($admin);
            $request->session()->regenerate();

            // Track successful login
            $this->adminSecurityService->trackSuccessfulLogin($admin, $request);

            return redirect()->route('admin.dashboard.main')
                ->with('success', 'Welcome back, ' . $admin->name . '!');
        }

        return back()->with('error', 'Invalid verification code. Please try again.');
    }

    /**
     * Show 2FA setup form
     */
    public function showSetupForm()
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin->two_factor_secret) {
            $admin->generateTwoFactorSecret();
        }

        $qrCodeSvg = $admin->getTwoFactorQrCodeSvg();

        return view('admin.auth.2fa-setup', compact('qrCodeSvg'));
    }

    /**
     * Enable 2FA for the authenticated admin
     */
    public function enable(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $admin = Auth::guard('admin')->user();

        if ($this->adminSecurityService->enableTwoFactor($admin, $request->code)) {
            $recoveryCodes = $admin->generateRecoveryCodes();
            
            return redirect()->route('admin.2fa.recovery-codes')
                ->with('success', '2FA has been enabled successfully!')
                ->with('recovery_codes', $recoveryCodes);
        }

        return back()->with('error', 'Invalid verification code. Please try again.');
    }

    /**
     * Disable 2FA for the authenticated admin
     */
    public function disable(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $admin = Auth::guard('admin')->user();

        if (!Hash::check($request->password, $admin->password)) {
            return back()->with('error', 'Incorrect password');
        }

        $this->adminSecurityService->disableTwoFactor($admin);

        return redirect()->route('admin.profile')
            ->with('warning', '2FA has been disabled. Your account security is reduced.');
    }

    /**
     * Show recovery codes
     */
    public function showRecoveryCodes()
    {
        $admin = Auth::guard('admin')->user();
        $recoveryCodes = session('recovery_codes', $admin->two_factor_recovery_codes ?? []);

        return view('admin.auth.2fa-recovery-codes', compact('recoveryCodes'));
    }

    /**
     * Regenerate recovery codes
     */
    public function regenerateRecoveryCodes()
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin->hasTwoFactorEnabled()) {
            return back()->with('error', '2FA must be enabled first');
        }

        $recoveryCodes = $admin->generateRecoveryCodes();

        return back()->with('success', 'New recovery codes generated')
            ->with('recovery_codes', $recoveryCodes);
    }
}