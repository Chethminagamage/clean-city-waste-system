<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Models\WasteReport;
use App\Models\User;
use Carbon\Carbon;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class AdminProfileController extends Controller
{
    public function edit()
    {
        $admin = Auth::guard('admin')->user();
        
        // Generate QR code and secret if 2FA is not enabled
        $secret = null;
        $qrCode = null;
        
        if (!$admin->two_factor_enabled) {
            $google2fa = new Google2FA();
            $secret = $google2fa->generateSecretKey();
            
            // Generate QR Code
            $qrCodeUrl = $google2fa->getQRCodeUrl(
                'CleanCity Admin',
                $admin->email,
                $secret
            );
            
            $renderer = new ImageRenderer(
                new RendererStyle(150),
                new SvgImageBackEnd()
            );
            
            $writer = new Writer($renderer);
            $qrCode = $writer->writeString($qrCodeUrl);
        }
        
        return view('admin.profile', compact('admin', 'secret', 'qrCode'));
    }

    public function update(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', Rule::unique('admins')->ignore($admin->id)],
            'phone'    => 'nullable|string|max:20',
            'position' => 'nullable|string|max:100',
            'bio'      => 'nullable|string|max:500',
            'password' => 'nullable|confirmed|min:6',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($admin->profile_photo) {
                Storage::disk('public')->delete($admin->profile_photo);
            }
            
            $photoPath = $request->file('profile_photo')->store('admin_photos', 'public');
            $admin->profile_photo = $photoPath;
        }

        // Update basic info
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;
        $admin->position = $request->position;
        $admin->bio = $request->bio;

        // Update password if provided
        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return redirect()->route('admin.profile.edit')->with('success', 'Profile updated successfully.');
    }

    public function removePhoto()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->profile_photo) {
            Storage::disk('public')->delete($admin->profile_photo);
            $admin->profile_photo = null;
            $admin->save();
            
            return redirect()->route('admin.profile.edit')->with('success', 'Profile photo removed successfully.');
        }
        
        return redirect()->route('admin.profile.edit')->with('error', 'No profile photo to remove.');
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
            'two_factor_enabled' => 1,
            'two_factor_secret' => $request->secret,
            'two_factor_recovery_codes' => json_encode($this->generateRecoveryCodes())
        ]);

        return redirect()->route('admin.profile.edit')
            ->with('success', 'Two-factor authentication has been enabled successfully!');
    }

    public function disableTwoFactor()
    {
        $admin = Auth::guard('admin')->user();
        
        $admin->update([
            'two_factor_enabled' => 0,
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null
        ]);

        return redirect()->route('admin.profile.edit')
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