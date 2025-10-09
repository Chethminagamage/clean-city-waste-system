<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\AdminSecurityService;
use Symfony\Component\HttpFoundation\Response;

/**
 * Professional Admin Session Security Middleware
 * Handles session timeout, concurrent sessions, and activity monitoring
 */
class AdminSessionSecurity
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
        // Only apply to authenticated admin sessions
        if (!Auth::guard('admin')->check()) {
            return $next($request);
        }

        $admin = Auth::guard('admin')->user();

        // Check for session hijacking attempts
        if ($this->detectSessionHijacking($request, $admin)) {
            $this->adminSecurityService->logSecurityEvent(
                AdminSecurityService::EVENT_SESSION_HIJACK,
                $admin->id,
                [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'session_id' => session()->getId()
                ]
            );

            // Force logout for security
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')
                ->with('error', 'Suspicious session activity detected. Please log in again.');
        }

        // Enforce session timeout (30 minutes of inactivity)
        $lastActivity = $request->session()->get('admin_last_activity');
        if ($lastActivity && now()->diffInMinutes($lastActivity) > 30) {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')
                ->with('error', 'Session expired due to inactivity. Please log in again.');
        }

        // Check if account has been locked since login
        if ($admin->hasLockTimestamp() && $admin->locked_until->isFuture()) {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            
            return redirect()->route('admin.login')
                ->with('error', 'Your account has been temporarily locked. Contact administrator.');
        }

        // Check if account has been deactivated
        if (!$admin->is_active) {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            
            return redirect()->route('admin.login')
                ->with('error', 'Your account has been deactivated. Contact administrator.');
        }

        // Update session activity
        $request->session()->put('admin_last_activity', now());

        // Track admin activity for security monitoring
        $this->trackAdminActivity($request, $admin);

        return $next($request);
    }

    /**
     * Detect potential session hijacking
     */
    private function detectSessionHijacking(Request $request, $admin): bool
    {
        $suspicious = false;

        // Check for rapid IP changes (possible session hijacking)
        $sessionIp = session('admin_login_ip');
        if ($sessionIp && $sessionIp !== $request->ip()) {
            $suspicious = true;
        }

        // Check for user agent changes (possible session hijacking)
        $sessionUserAgent = session('admin_user_agent');
        if ($sessionUserAgent && $sessionUserAgent !== $request->userAgent()) {
            $suspicious = true;
        }

        return $suspicious;
    }

    /**
     * Track admin activity for security monitoring
     */
    private function trackAdminActivity(Request $request, $admin): void
    {
        // Store session fingerprint on first request
        if (!session()->has('admin_login_ip')) {
            session([
                'admin_login_ip' => $request->ip(),
                'admin_user_agent' => $request->userAgent(),
                'admin_session_start' => now()
            ]);
        }

        // Log high-privilege actions
        if ($this->isHighPrivilegeAction($request)) {
            $this->adminSecurityService->logSecurityEvent(
                'high_privilege_action',
                $admin->id,
                [
                    'route' => $request->route()?->getName(),
                    'method' => $request->method(),
                    'ip' => $request->ip()
                ]
            );
        }
    }

    /**
     * Check if the current action requires high privileges
     */
    private function isHighPrivilegeAction(Request $request): bool
    {
        $highPrivilegeRoutes = [
            'admin.users.delete',
            'admin.users.toggle',
            'admin.2fa.disable',
            'admin.collectors.store',
            'admin.collectors.update',
            'admin.collectors.destroy'
        ];

        $routeName = $request->route()?->getName();
        return in_array($routeName, $highPrivilegeRoutes);
    }
}
