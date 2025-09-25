<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Services\AutoLogoutService;

class ActivityController extends Controller
{
    protected $autoLogoutService;

    public function __construct(AutoLogoutService $autoLogoutService)
    {
        $this->autoLogoutService = $autoLogoutService;
    }

    /**
     * Handle activity ping from frontend
     */
    public function ping(Request $request)
    {
        // Determine which guard is active
        $currentGuard = $this->getCurrentGuard();
        
        if (!$currentGuard || !Auth::guard($currentGuard)->check()) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        // Update activity timestamp
        $this->autoLogoutService->updateActivity($currentGuard);

        // Return session status information
        return response()->json([
            'status' => 'active',
            'guard' => $currentGuard,
            'user_type' => $this->autoLogoutService->getUserTypeName($currentGuard),
            'time_remaining' => $this->autoLogoutService->getTimeRemaining($currentGuard),
            'near_timeout' => $this->autoLogoutService->isNearTimeout($currentGuard),
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Get session status information
     */
    public function status(Request $request)
    {
        $currentGuard = $this->getCurrentGuard();
        
        if (!$currentGuard || !Auth::guard($currentGuard)->check()) {
            return response()->json(['authenticated' => false], 401);
        }

        return response()->json([
            'authenticated' => true,
            'guard' => $currentGuard,
            'user_type' => $this->autoLogoutService->getUserTypeName($currentGuard),
            'time_remaining' => $this->autoLogoutService->getTimeRemaining($currentGuard),
            'near_timeout' => $this->autoLogoutService->isNearTimeout($currentGuard),
            'timeout_minutes' => AutoLogoutService::TIMEOUT_MINUTES
        ]);
    }

    /**
     * Extend user session
     */
    public function extend(Request $request)
    {
        $currentGuard = $this->getCurrentGuard();
        
        if (!$currentGuard || !Auth::guard($currentGuard)->check()) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        // Reset activity timer
        $this->autoLogoutService->updateActivity($currentGuard);

        return response()->json([
            'success' => true,
            'message' => 'Session extended successfully',
            'new_timeout' => now()->addMinutes(AutoLogoutService::TIMEOUT_MINUTES)->toISOString()
        ]);
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