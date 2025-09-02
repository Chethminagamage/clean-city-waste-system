<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function toggle(Request $request)
    {
        // Get current theme from session (default to 'light')
        $currentTheme = $request->session()->get('theme', 'light');
        
        // Toggle theme
        $newTheme = $currentTheme === 'light' ? 'dark' : 'light';
        
        // Store in session
        $request->session()->put('theme', $newTheme);
        
        // Return JSON response for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['theme' => $newTheme]);
        }
        
        // Return to previous page for non-AJAX requests
        return back();
    }
}
