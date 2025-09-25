<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class SecurityDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $admin = Auth::guard('admin')->user();
        
        // Generate QR code and secret if 2FA is not enabled
        $secret = null;
        $qrCode = null;
        
        if (!$admin->two_factor_secret) {
            $google2fa = new Google2FA();
            $secret = $google2fa->generateSecretKey();
            
            // Generate QR Code
            $qrCodeUrl = $google2fa->getQRCodeUrl(
                'CleanCity Admin',
                $admin->email,
                $secret
            );
            
            $renderer = new ImageRenderer(
                new RendererStyle(200),
                new SvgImageBackEnd()
            );
            
            $writer = new Writer($renderer);
            $qrCode = $writer->writeString($qrCodeUrl);
        }
        
        return view('admin.security.2fa-settings', [
            'admin' => $admin,
            'secret' => $secret,
            'qrCode' => $qrCode
        ]);
    }

    public function enableTwoFactor(Request $request)
    {
        $request->validate([
            'secret' => 'required|string',
            'code' => 'required|string|size:6'
        ]);

        $admin = Auth::guard('admin')->user();
        $google2fa = new Google2FA();

        $valid = $google2fa->verifyKey($request->secret, $request->code);

        if (!$valid) {
            throw ValidationException::withMessages([
                'code' => 'The verification code is invalid.'
            ]);
        }

        $admin->update([
            'two_factor_secret' => $request->secret,
            'two_factor_recovery_codes' => json_encode($this->generateRecoveryCodes())
        ]);

        return redirect()->route('admin.security.2fa')
            ->with('success', 'Two-factor authentication has been enabled successfully!');
    }

    public function disableTwoFactor()
    {
        $admin = Auth::guard('admin')->user();
        
        $admin->update([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null
        ]);

        return redirect()->route('admin.security.2fa')
            ->with('success', 'Two-factor authentication has been disabled.');
    }

    private function generateRecoveryCodes()
    {
        $codes = [];
        for ($i = 0; $i < 8; $i++) {
            $codes[] = strtoupper(substr(md5(rand()), 0, 8));
        }
        return $codes;
    }
}
