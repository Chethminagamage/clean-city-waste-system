<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class AutoLogoutService
{
    /**
     * Auto logout timeout in minutes
     */
    const TIMEOUT_MINUTES = 25;
    
    /**
     * Check if user should be auto-logged out
     */
    public function shouldLogout(string $guard): bool
    {
        if (!Auth::guard($guard)->check()) {
            return false;
        }
        
        $sessionKey = 'last_activity_' . $guard;
        $lastActivity = Session::get($sessionKey);
        
        // If no activity recorded, return false (user just logged in)
        if (!$lastActivity) {
            return false;
        }
        
        // Convert to Carbon if it's a string
        if (is_string($lastActivity)) {
            $lastActivity = \Carbon\Carbon::parse($lastActivity);
        }
        
        $timeoutSeconds = self::TIMEOUT_MINUTES * 60;
        
        return $lastActivity->diffInSeconds(now()) > $timeoutSeconds;
    }
    
    /**
     * Perform auto logout for the specified guard
     */
    public function performLogout(string $guard, array $logData = []): void
    {
        $user = Auth::guard($guard)->user();
        
        // Log the auto-logout event
        Log::info('Auto-logout performed', array_merge([
            'user_id' => $user ? $user->id : null,
            'guard' => $guard,
            'timeout_minutes' => self::TIMEOUT_MINUTES,
            'timestamp' => now()->toISOString()
        ], $logData));
        
        // Logout the user
        Auth::guard($guard)->logout();
        Session::invalidate();
        Session::regenerateToken();
    }
    
    /**
     * Update user activity timestamp
     */
    public function updateActivity(string $guard): void
    {
        if (Auth::guard($guard)->check()) {
            $sessionKey = 'last_activity_' . $guard;
            Session::put($sessionKey, now());
        }
    }
    
    /**
     * Get login route for the specified guard
     */
    public function getLoginRoute(string $guard): string
    {
        return match ($guard) {
            'admin' => 'admin.login',
            'collector' => 'collector.login',
            'web' => 'login',
            default => 'login'
        };
    }
    
    /**
     * Get user type name for display
     */
    public function getUserTypeName(string $guard): string
    {
        return match ($guard) {
            'admin' => 'Administrator',
            'collector' => 'Waste Collector',
            'web' => 'Resident',
            default => 'User'
        };
    }
    
    /**
     * Get time remaining before auto-logout (in seconds)
     */
    public function getTimeRemaining(string $guard): int
    {
        if (!Auth::guard($guard)->check()) {
            return 0;
        }
        
        $sessionKey = 'last_activity_' . $guard;
        $lastActivity = Session::get($sessionKey);
        
        // If no activity recorded, return full timeout period
        if (!$lastActivity) {
            return self::TIMEOUT_MINUTES * 60;
        }
        
        // Convert to Carbon if it's a string
        if (is_string($lastActivity)) {
            $lastActivity = \Carbon\Carbon::parse($lastActivity);
        }
        
        $timeoutSeconds = self::TIMEOUT_MINUTES * 60;
        $elapsed = $lastActivity->diffInSeconds(now());
        
        return max(0, $timeoutSeconds - $elapsed);
    }
    
    /**
     * Check if user is close to timeout (within 5 minutes)
     */
    public function isNearTimeout(string $guard): bool
    {
        $remaining = $this->getTimeRemaining($guard);
        return $remaining > 0 && $remaining <= 300; // 5 minutes
    }
}