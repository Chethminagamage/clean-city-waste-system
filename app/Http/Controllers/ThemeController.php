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
            
            // Check for authenticated user (resident)
            if (Auth::check()) {
                $user = Auth::user();
                $currentTheme = $user->theme_preference ?? 'light';
                
                \Log::info('Resident found', [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'current_theme' => $currentTheme,
                    'guard' => 'default'
                ]);
                
                // Toggle between light and dark
                $newTheme = $currentTheme === 'dark' ? 'light' : 'dark';
                
                \Log::info('Toggling resident theme', ['from' => $currentTheme, 'to' => $newTheme]);
                
                // Save to database
                $updated = $user->update([
                    'theme_preference' => $newTheme
                ]);
                
                // Verify the update
                $user->refresh();
                $actualTheme = $user->theme_preference;
                
                \Log::info('Resident database update result', [
                    'update_success' => $updated,
                    'expected_theme' => $newTheme,
                    'actual_theme' => $actualTheme,
                    'themes_match' => $newTheme === $actualTheme
                ]);
                
                return response()->json([
                    'success' => true,
                    'theme' => $actualTheme,
                    'message' => 'User theme preference updated successfully',
                    'debug' => [
                        'user_id' => $user->id,
                        'from' => $currentTheme,
                        'to' => $actualTheme
                    ]
                ]);
            }

            // For guests, just return current opposite of light
            \Log::warning('No authenticated user found', [
                'collector_check' => Auth::guard('collector')->check(),
                'user_check' => Auth::check(),
                'session_id' => session()->getId(),
                'request_method' => $request->method(),
                'request_url' => $request->url()
            ]);
            
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
        \Log::info('setTheme method called', [
            'user_agent' => $request->userAgent(),
            'ip' => $request->ip(),
            'method' => $request->method(),
            'theme_param' => $request->theme,
            'all_params' => $request->all()
        ]);
        
        $request->validate([
            'theme' => 'required|in:light,dark,system'
        ]);

        $theme = $request->theme;
        
        \Log::info('Theme validation passed', ['theme' => $theme]);

        try {
            // Check for authenticated collector first
            if (Auth::guard('collector')->check()) {
                $collector = Auth::guard('collector')->user();
                
                \Log::info('Collector authenticated for setTheme', [
                    'id' => $collector->id,
                    'name' => $collector->name,
                    'theme' => $theme
                ]);
                
                $updated = $collector->update(['theme_preference' => $theme]);
                
                \Log::info('Collector theme update result', [
                    'success' => $updated,
                    'theme' => $theme
                ]);
                
                return response()->json([
                    'success' => true,
                    'theme' => $theme,
                    'message' => 'Collector theme preference updated successfully'
                ]);
            }
            
            // Check for authenticated user (resident)
            if (Auth::check()) {
                $user = Auth::user();
                
                \Log::info('Resident authenticated for setTheme', [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'theme' => $theme
                ]);
                
                $updated = $user->update(['theme_preference' => $theme]);
                
                \Log::info('Resident theme update result', [
                    'success' => $updated,
                    'theme' => $theme,
                    'user_id' => $user->id
                ]);
                
                return response()->json([
                    'success' => true,
                    'theme' => $theme,
                    'message' => 'User theme preference updated successfully'
                ]);
            }

            \Log::warning('No authenticated user found for setTheme', [
                'collector_check' => Auth::guard('collector')->check(),
                'user_check' => Auth::check(),
                'session_id' => session()->getId()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
            
        } catch (\Exception $e) {
            \Log::error('setTheme exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to set theme: ' . $e->getMessage()
            ], 500);
        }
    }
}