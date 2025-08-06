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
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'contact' => 'nullable|string|max:20',
            'current_password' => 'required_with:new_password|nullable|string',
            'new_password' => 'nullable|string|min:8|confirmed',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        // ✅ Basic fields
        $user->name = $request->name;
        $user->email = $request->email;
        $user->contact = $request->contact;

        $passwordChanged = false;

        // ✅ Handle new password
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }

            $user->password = Hash::make($request->new_password);
            $passwordChanged = true;
        }

        // ✅ Handle profile image upload
        if ($request->hasFile('profile_image')) {
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $path = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $path;
        }

        // ✅ Handle 2FA toggle
        $user->two_factor_enabled = $request->has('two_factor_enabled');

        $user->save();

        // ✅ Send password changed email notification
        if ($passwordChanged) {
            Mail::to($user->email)->send(new PasswordChangedNotification($user));
        }

        return back()->with('success', 'Profile updated successfully.');
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
}