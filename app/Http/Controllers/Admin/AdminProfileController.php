<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\WasteReport;
use App\Models\User;
use Carbon\Carbon;

class AdminProfileController extends Controller
{
    public function edit()
    {
        $admin = Auth::guard('admin')->user();
        
        return view('admin.profile', compact('admin'));
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
}