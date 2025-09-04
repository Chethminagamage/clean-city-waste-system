<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Mail\PasswordChangedNotification;
use Illuminate\Support\Facades\Mail;

class ResidentProfileController extends Controller
{
    public function edit()
    {
        return view('resident.profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $action = $request->input('action', 'info');
        $user = Auth::user();

        switch ($action) {
            case 'photo':
                return $this->updatePhoto($request, $user);
            case 'password':
                return $this->updatePassword($request, $user);
            case 'theme':
                return $this->updateTheme($request, $user);
            case 'info':
            default:
                return $this->updateInfo($request, $user);
        }
    }

    private function updatePhoto(Request $request, $user)
    {
        // Only validate if there's actually a file being uploaded
        if ($request->hasFile('profile_image')) {
            $request->validate([
                'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Delete old image if exists
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }

            // Store new image
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $path;
            $user->save();

            return back()->with('success', 'Profile photo updated successfully.');
        }

        return back()->withErrors(['profile_image' => 'Please select an image to upload.']);
    }

    private function updatePassword(Request $request, $user)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Send password changed email notification
        Mail::to($user->email)->send(new PasswordChangedNotification($user));

        return back()->with('success', 'Password updated successfully.');
    }

    private function updateInfo(Request $request, $user)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'contact' => 'nullable|string|max:20',
        ]);

        // Update basic fields
        $user->name = $request->name;
        $user->email = $request->email;
        $user->contact = $request->contact;
        
        $user->save();

        return back()->with('success', 'Profile information updated successfully.');
    }

    private function updateTheme(Request $request, $user)
    {
        $request->validate([
            'theme_preference' => 'required|in:light,dark,auto',
        ]);

        $user->theme_preference = $request->theme_preference;
        $user->save();

        return back()->with('success', 'Theme preference updated successfully.');
    }

    public function removeImage()
    {
        $user = Auth::user();

        if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
            Storage::disk('public')->delete($user->profile_image);
        }

        $user->profile_image = null;
        $user->save();

        return back()->with('success', 'Profile image removed.');
    }

    public function toggle2FA(Request $request)
    {
        $user = Auth::user();
        
        // Set based on checkbox state - if checked, enable; if unchecked (not sent), disable
        $user->two_factor_enabled = $request->has('two_factor_enabled') ? 1 : 0;
        $user->save();

        $status = $user->two_factor_enabled ? 'enabled' : 'disabled';
        return back()->with('success', "Two-factor authentication has been {$status}.");
    }
}