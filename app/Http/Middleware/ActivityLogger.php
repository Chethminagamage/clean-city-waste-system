<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    protected $activityLogger;

    // Routes to exclude from automatic logging
    protected $excludedRoutes = [
        'notifications.*',
        '*.api.*',
        'livewire.*',
        '_debugbar.*',
        'horizon.*',
        'telescope.*'
    ];

    // Routes that should only log on specific methods
    protected $methodSpecificRoutes = [
        'POST', 'PUT', 'PATCH', 'DELETE'
    ];

    // Routes that should be logged regardless of method
    protected $alwaysLogRoutes = [
        '*.dashboard*',
        'admin.*',
        'collector.dashboard',
        '*.profile*',
        '*.login*',
        '*.logout*'
    ];

    public function __construct(ActivityLogService $activityLogger)
    {
        $this->activityLogger = $activityLogger;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        
        $response = $next($request);
        
        // Don't log if route should be excluded
        if ($this->shouldExclude($request)) {
            return $response;
        }

        // Don't log failed requests (4xx, 5xx) as they're handled elsewhere
        if ($response->getStatusCode() >= 400) {
            return $response;
        }

        // Log the activity
        $this->logActivity($request, $response, $startTime);

        return $response;
    }

    /**
     * Log the activity
     */
    protected function logActivity($request, $response, $startTime)
    {
        $user = $this->getCurrentUser();
        $routeName = $request->route()?->getName() ?? 'unknown';
        $method = $request->method();
        
        // Determine if we should log this request
        if (!$this->shouldLog($request, $routeName, $method)) {
            return;
        }

        try {
            $this->activityLogger->logPageAccess(
                $this->getPageName($routeName, $request->path()),
                $user
            );
        } catch (\Exception $e) {
            // Log error but don't break the request
            \Log::error('Activity logging failed: ' . $e->getMessage());
        }
    }

    /**
     * Check if request should be excluded
     */
    protected function shouldExclude($request)
    {
        $routeName = $request->route()?->getName();
        
        if (!$routeName) {
            return true;
        }

        foreach ($this->excludedRoutes as $pattern) {
            if ($this->matchesPattern($routeName, $pattern)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if request should be logged
     */
    protected function shouldLog($request, $routeName, $method)
    {
        // Always log certain routes regardless of method
        foreach ($this->alwaysLogRoutes as $pattern) {
            if ($this->matchesPattern($routeName, $pattern)) {
                return true;
            }
        }

        // Log only specific methods for other routes
        if (in_array($method, $this->methodSpecificRoutes)) {
            return true;
        }

        // Log GET requests to authenticated pages
        if ($method === 'GET' && $this->isAuthenticatedRoute($routeName)) {
            return true;
        }

        return false;
    }

    /**
     * Check if route requires authentication
     */
    protected function isAuthenticatedRoute($routeName)
    {
        $authenticatedPatterns = [
            'admin.*',
            'collector.*',
            'resident.*',
            '*.dashboard*',
            '*.profile*'
        ];

        foreach ($authenticatedPatterns as $pattern) {
            if ($this->matchesPattern($routeName, $pattern)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Match route name against pattern
     */
    protected function matchesPattern($routeName, $pattern)
    {
        $pattern = str_replace('*', '.*', $pattern);
        return preg_match('/^' . $pattern . '$/', $routeName);
    }

    /**
     * Get human-readable page name
     */
    protected function getPageName($routeName, $path)
    {
        // Map route names to human-readable names
        $nameMapping = [
            'home' => 'Homepage',
            'login' => 'Login Page',
            'register' => 'Registration Page',
            'admin.dashboard.main' => 'Admin Dashboard',
            'admin.binreports' => 'Admin Bin Reports',
            'admin.collectors' => 'Admin Collector Management',
            'admin.users' => 'Admin User Management',
            'admin.profile.edit' => 'Admin Profile',
            'collector.dashboard' => 'Collector Dashboard',
            'collector.profile' => 'Collector Profile',
            'collector.reports.all' => 'Collector Reports',
            'resident.dashboard.main' => 'Resident Dashboard',
            'resident.profile.edit' => 'Resident Profile',
            'resident.reports.index' => 'Resident Report History',
            'resident.feedback.index' => 'Resident Feedback',
            'resident.gamification.index' => 'Resident Gamification',
        ];

        return $nameMapping[$routeName] ?? $this->formatRouteName($routeName, $path);
    }

    /**
     * Format route name into readable text
     */
    protected function formatRouteName($routeName, $path)
    {
        if ($routeName) {
            return ucwords(str_replace(['.', '_'], ' ', $routeName));
        }
        
        return ucwords(str_replace(['/', '-', '_'], ' ', trim($path, '/'))) ?: 'Page';
    }

    /**
     * Get current authenticated user from any guard
     */
    protected function getCurrentUser()
    {
        if (Auth::guard('web')->check()) {
            return Auth::guard('web')->user();
        } elseif (Auth::guard('admin')->check()) {
            return Auth::guard('admin')->user();
        } elseif (Auth::guard('collector')->check()) {
            return Auth::guard('collector')->user();
        }
        
        return null;
    }
}
