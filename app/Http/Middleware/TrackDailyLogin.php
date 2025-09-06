<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Services\GamificationService;
use Carbon\Carbon;

class TrackDailyLogin
{
    protected $gamificationService;

    public function __construct(GamificationService $gamificationService)
    {
        $this->gamificationService = $gamificationService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only track for authenticated residents
        if (Auth::check() && Auth::user()->role === 'resident') {
            $user = Auth::user();
            $today = Carbon::today();
            
            // Check if user hasn't logged in today
            if (!$user->last_login_at || !$user->last_login_at->isToday()) {
                try {
                    // Update last login time
                    $user->update(['last_login_at' => now()]);
                    
                    // Award daily login points
                    $this->gamificationService->awardPoints(
                        $user,
                        'daily_login',
                        null,
                        'Daily login bonus',
                        [
                            'login_date' => $today->toDateString(),
                            'consecutive_days' => $this->getConsecutiveDays($user)
                        ]
                    );
                } catch (\Exception $e) {
                    // Log error but don't fail the request
                    \Log::error('Failed to track daily login: ' . $e->getMessage());
                }
            }
        }

        return $response;
    }

    /**
     * Calculate consecutive login days
     */
    private function getConsecutiveDays($user): int
    {
        if (!$user->last_login_at) {
            return 1;
        }

        $yesterday = Carbon::yesterday();
        if ($user->last_login_at->isYesterday()) {
            // User logged in yesterday, continue streak
            // This is a simplified calculation - you could store streak data separately for more accuracy
            return 2; // This would need more complex logic for actual consecutive counting
        }

        // If more than 1 day gap, streak is broken
        return 1;
    }
}
