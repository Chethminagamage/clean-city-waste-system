<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThemeController extends Controller
{
    /**
     * Toggle user's theme preference
     */
    public function toggle(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $currentTheme = $user->theme_preference ?? 'light';
            
            // Toggle between light and dark
            $newTheme = $currentTheme === 'dark' ? 'light' : 'dark';
            
            // Save to database
            $user->update([
                'theme_preference' => $newTheme
            ]);
            
            return response()->json([
                'success' => true,
                'theme' => $newTheme,
                'message' => 'Theme preference updated successfully'
            ]);
        }

        // For guests, just return current opposite of light
        return response()->json([
            'success' => true,
            'theme' => 'dark',
            'message' => 'Theme toggled (guest mode)'
        ]);
    }

    /**
     * Get current user's theme preference
     */
    public function getTheme()
    {
        if (Auth::check()) {
            return response()->json([
                'theme' => Auth::user()->theme_preference ?? 'light'
            ]);
        }

        return response()->json([
            'theme' => 'light'
        ]);
    }

    /**
     * Set user's theme preference to a specific value
     */
    public function setTheme(Request $request)
    {
        $request->validate([
            'theme' => 'required|in:light,dark,auto'
        ]);

        if (Auth::check()) {
            $user = Auth::user();
            
            // Save to database
            $user->update([
                'theme_preference' => $request->theme
            ]);

            return response()->json([
                'success' => true,
                'theme' => $request->theme,
                'message' => 'Theme preference updated successfully'
            ]);
        }

        // For guests, just return the requested theme
        return response()->json([
            'success' => true,
            'theme' => $request->theme,
            'message' => 'Theme set (guest mode)'
        ]);
    }
}
