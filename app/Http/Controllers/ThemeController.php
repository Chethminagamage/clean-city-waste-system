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
        \Log::info('Theme toggle called', [
            'user_agent' => $request->userAgent(),
            'ip' => $request->ip(),
            'method' => $request->method()
        ]);
        
        try {
            // Check for authenticated collector first
            if (Auth::guard('collector')->check()) {
                $collector = Auth::guard('collector')->user();
                $currentTheme = $collector->theme_preference ?? 'light';
                
                \Log::info('Collector found', [
                    'id' => $collector->id,
                    'name' => $collector->name,
                    'current_theme' => $currentTheme
                ]);
                
                // Toggle between light and dark
                $newTheme = $currentTheme === 'dark' ? 'light' : 'dark';
                
                \Log::info('Toggling theme', ['from' => $currentTheme, 'to' => $newTheme]);
                
                // Save to database
                $updated = $collector->update([
                    'theme_preference' => $newTheme
                ]);
                
                // Verify the update
                $collector->refresh();
                $actualTheme = $collector->theme_preference;
                
                \Log::info('Database update result', [
                    'update_success' => $updated,
                    'expected_theme' => $newTheme,
                    'actual_theme' => $actualTheme,
                    'themes_match' => $newTheme === $actualTheme
                ]);
                
                return response()->json([
                    'success' => true,
                    'theme' => $actualTheme,
                    'message' => 'Collector theme preference updated successfully',
                    'debug' => [
                        'collector_id' => $collector->id,
                        'from' => $currentTheme,
                        'to' => $actualTheme
                    ]
                ]);
            }
        
        // Check for authenticated user
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
                'message' => 'User theme preference updated successfully'
                ]);
            }
            
            // Check for authenticated user
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
                    'message' => 'User theme preference updated successfully'
                ]);
            }

            // For guests, just return current opposite of light
            return response()->json([
                'success' => true,
                'theme' => 'dark',
                'message' => 'Theme toggled (guest mode)'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Theme toggle failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle theme: ' . $e->getMessage()
            ], 500);
        }
    }    /**
     * Get current user's theme preference
     */
    public function getTheme()
    {
        // Check for authenticated collector first
        if (Auth::guard('collector')->check()) {
            return response()->json([
                'theme' => Auth::guard('collector')->user()->theme_preference ?? 'light'
            ]);
        }
        
        // Check for authenticated user
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
            'theme' => 'required|in:light,dark,system'
        ]);

        $theme = $request->theme;

        if (Auth::check()) {
            // Update user theme preference
            Auth::user()->update(['theme_preference' => $theme]);
        } elseif (Auth::guard('collector')->check()) {
            // Update collector theme preference
            Auth::guard('collector')->user()->update(['theme_preference' => $theme]);
        }
}
}