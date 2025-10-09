<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\AdminSecurityService;
use Symfony\Component\HttpFoundation\Response;

/**
 * Professional Admin IP Security Middleware
 * Enforces IP whitelisting and advanced security monitoring
 */
class AdminIPSecurity
{
    protected $adminSecurityService;

    public function __construct(AdminSecurityService $adminSecurityService)
    {
        $this->adminSecurityService = $adminSecurityService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip IP checks for login page (handled in LoginController)
        if ($request->is('admin/login') && $request->method() === 'GET') {
            return $next($request);
        }

        // Check if admin is authenticated
        if (!Auth::guard('admin')->check()) {
            return $next($request);
        }

        $admin = Auth::guard('admin')->user();

        // Check IP whitelist
        if (!$this->adminSecurityService->isIpAllowed($request->ip())) {
            $this->adminSecurityService->logSecurityEvent(
                AdminSecurityService::EVENT_UNAUTHORIZED_IP,
                $admin->id,
                [
                    'ip' => $request->ip(),
                    'route' => $request->route()->getName(),
                    'user_agent' => $request->userAgent()
                ]
            );

            // Force logout
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')
                ->with('error', 'Access denied: Unauthorized IP address detected. You have been logged out for security.');
        }

        // Check session timeout
        if ($this->isSessionExpired($request)) {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')
                ->with('error', 'Session expired for security. Please log in again.');
        }

        // Update last activity
        $request->session()->put('admin_last_activity', now());

        // Detect suspicious activity patterns
        if ($this->adminSecurityService->detectSuspiciousActivity($admin, $request)) {
            $this->adminSecurityService->logSecurityEvent(
                AdminSecurityService::EVENT_SUSPICIOUS_ACTIVITY,
                $admin->id,
                [
                    'route' => $request->route()->getName(),
                    'previous_ip' => $admin->last_login_ip,
                    'current_ip' => $request->ip(),
                    'time_since_last_login' => $admin->last_login_at?->diffInMinutes(now())
                ]
            );
        }

        return $next($request);
    }

    /**
     * Check if the admin session has expired
     */
    private function isSessionExpired(Request $request): bool
    {
        $sessionTimeout = $request->session()->get('admin_session_timeout');
        
        if (!$sessionTimeout) {
            return false;
        }

        return now()->gt($sessionTimeout);
    }
}
