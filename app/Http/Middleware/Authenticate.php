<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            $uri = $request->path();
            
            // Redirect based on URL path for unauthenticated users
            if (Str::startsWith($uri, 'admin')) {
                return route('admin.login');
            }
            
            if (Str::startsWith($uri, 'collector')) {
                return route('collector.login');
            }
            
            // Default to resident login
            return route('login');
        }
        
        return null;
    }

    /**
     * Handle an unauthenticated user.
     */
    protected function unauthenticated($request, array $guards)
    {
        // Check if user is authenticated on a different guard and determine their type
        $currentUserType = null;
        $currentGuard = null;
        
        if (auth('web')->check()) {
            $currentUserType = 'Resident';
            $currentGuard = 'web';
        } elseif (auth('admin')->check()) {
            $currentUserType = 'Admin';
            $currentGuard = 'admin';
        } elseif (auth('collector')->check()) {
            $currentUserType = 'Collector';
            $currentGuard = 'collector';
        }
        
        if ($currentUserType) {
            // User is logged in but accessing wrong guard's routes
            // Redirect to the appropriate login page for the area they're trying to access
            
            // Log security attempt for monitoring
            \Log::info('Cross-guard access redirected to login', [
                'current_user_type' => $currentUserType,
                'user_id' => auth($currentGuard)->id(),
                'attempted_guards' => $guards,
                'attempted_url' => $request->url(),
                'ip' => $request->ip()
            ]);
            
            // Redirect to appropriate login based on what they're trying to access
            if (in_array('admin', $guards)) {
                return redirect()->route('admin.login')
                    ->withHeaders([
                        'Cache-Control' => 'no-cache, no-store, must-revalidate',
                        'Pragma' => 'no-cache',
                        'Expires' => '0'
                    ]);
            } elseif (in_array('collector', $guards)) {
                return redirect()->route('collector.login')
                    ->withHeaders([
                        'Cache-Control' => 'no-cache, no-store, must-revalidate',
                        'Pragma' => 'no-cache', 
                        'Expires' => '0'
                    ]);
            } elseif (in_array('web', $guards)) {
                return redirect()->route('login') // resident login
                    ->withHeaders([
                        'Cache-Control' => 'no-cache, no-store, must-revalidate',
                        'Pragma' => 'no-cache',
                        'Expires' => '0'
                    ]);
            }
            
            // Fallback to landing page if guard not recognized
            return redirect('/')->withHeaders([
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ]);
        }

        // For unauthenticated users, use parent behavior
        throw new \Illuminate\Auth\AuthenticationException(
            'Unauthenticated.', $guards, $this->redirectTo($request)
        );
    }
}
