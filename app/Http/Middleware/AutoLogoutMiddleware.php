<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Services\AutoLogoutService;

class AutoLogoutMiddleware
{
    protected $autoLogoutService;

    public function __construct(AutoLogoutService $autoLogoutService)
    {
        $this->autoLogoutService = $autoLogoutService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip for activity tracking routes to avoid interference
        if ($request->is('activity-ping') || $request->is('activity-status') || $request->is('activity-extend')) {
            return $next($request);
        }

        // Skip for login routes to avoid redirect loops
        if ($request->is('login') || $request->is('admin/login') || $request->is('collector/login') || 
            $request->is('register') || $request->is('forgot-password') || $request->is('reset-password/*')) {
            return $next($request);
        }

        // Check if user is authenticated on any guard
        $currentGuard = $this->getCurrentGuard();
        
        if ($currentGuard && Auth::guard($currentGuard)->check()) {
            // Check if user should be auto-logged out
            if ($this->autoLogoutService->shouldLogout($currentGuard)) {
                // Determine redirect route and user type before logout
                $redirectRoute = $this->autoLogoutService->getLoginRoute($currentGuard);
                $userType = $this->autoLogoutService->getUserTypeName($currentGuard);
                
                // Handle AJAX requests
                if ($request->expectsJson() || $request->ajax()) {
                    // Perform logout for AJAX (no need for session message)
                    $this->autoLogoutService->performLogout($currentGuard, [
                        'ip' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'route' => $request->route() ? $request->route()->getName() : $request->path()
                    ]);
                    
                    return response()->json([
                        'message' => "Your {$userType} session has expired due to inactivity.",
                        'redirect' => route($redirectRoute, ['session_expired' => 1]),
                        'logout_reason' => 'inactivity_timeout'
                    ], 401);
                }
                
                // For regular requests, we need to preserve the message across session invalidation
                // Perform logout with logging
                $this->autoLogoutService->performLogout($currentGuard, [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'route' => $request->route() ? $request->route()->getName() : $request->path()
                ]);
                
                // Redirect with session expired parameter in URL
                return redirect()->route($redirectRoute, ['session_expired' => 1]);
            }
            
            // Update activity for non-AJAX page requests
            if (!$request->ajax() && !$request->expectsJson() && $request->isMethod('get')) {
                $this->autoLogoutService->updateActivity($currentGuard);
            }
        }
        
        return $next($request);
    }
    
    /**
     * Get the current active guard
     */
    private function getCurrentGuard(): ?string
    {
        $guards = ['admin', 'collector', 'web'];
        
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return $guard;
            }
        }
        
        return null;
    }
}