<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Services\GamificationService;
use App\Models\Achievement;
use App\Models\Reward;
use App\Models\UserAchievement;
use App\Models\RewardRedemption;
use App\Models\PointTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GamificationController extends Controller
{
    protected $gamificationService;

    public function __construct(GamificationService $gamificationService)
    {
        $this->gamificationService = $gamificationService;
    }

    /**
     * Show main gamification page (simplified)
     */
    public function index()
    {
        $user = Auth::user();
        $stats = $this->gamificationService->getUserStats($user);
        
        // Recent achievements (just a few)
        $recentAchievements = UserAchievement::with('achievement')
            ->where('user_id', $user->id)
            ->latest('earned_at')
            ->limit(3)
            ->get();
        
        // Top 5 users this week for simple leaderboard
        $weeklyLeaderboard = $this->gamificationService->getLeaderboard('weekly', null, 5);
        
        return view('resident.gamification.index', compact(
            'stats',
            'recentAchievements', 
            'weeklyLeaderboard'
        ));
    }

    /**
     * Show rewards store
     */
    public function rewards()
    {
        $user = Auth::user();
        $stats = $this->gamificationService->getUserStats($user);
        
        // Group rewards by type (simplified)
        $rewardTypes = ['badge', 'discount', 'physical', 'experience'];
        $rewardsByType = [];
        
        foreach ($rewardTypes as $type) {
            $rewards = Reward::where('type', $type)
                ->where('is_active', true)
                ->where(function($query) {
                    $query->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                })
                ->take(6) // Limit to 6 per category
                ->get();
                
            foreach ($rewards as $reward) {
                $reward->can_afford = $stats['total_points'] >= $reward->cost_points;
                $reward->remaining_quantity = $reward->getRemainingQuantity();
            }
            
            if ($rewards->isNotEmpty()) {
                $rewardsByType[$type] = $rewards;
            }
        }
        
        // User's recent redemptions
        $userRedemptions = RewardRedemption::with('reward')
            ->where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();
        
        return view('resident.gamification.rewards', compact(
            'rewardsByType',
            'stats',
            'userRedemptions'
        ));
    }

    /**
     * Redeem a reward
     */
    public function redeemReward(Request $request, Reward $reward)
    {
        $user = Auth::user();
        $stats = $this->gamificationService->getUserStats($user);
        
        // Check if reward is available
        if (!$reward->isAvailable()) {
            return back()->with('error', 'This reward is no longer available.');
        }
        
        // Check if user has enough points
        if ($stats['total_points'] < $reward->cost_points) {
            return back()->with('error', 'You need more points to redeem this reward.');
        }
        
        // Process redemption
        if ($this->gamificationService->spendPoints($user, $reward->cost_points, "Redeemed: {$reward->name}")) {
            $redemption = RewardRedemption::create([
                'user_id' => $user->id,
                'reward_id' => $reward->id,
                'points_spent' => $reward->cost_points,
                'status' => 'completed',
                'redeemed_at' => now(),
                'expires_at' => isset($reward->reward_data['validity_days']) 
                    ? now()->addDays($reward->reward_data['validity_days']) 
                    : null,
                'redemption_data' => $reward->reward_data
            ]);
            
            return back()->with('success', '🎉 Reward redeemed successfully! Your code: ' . $redemption->redemption_code);
        }
        
        return back()->with('error', 'Failed to redeem reward. Please try again.');
    }

    /**
     * API endpoint to get user gamification data
     */
    public function apiStats()
    {
        $user = Auth::user();
        $stats = $this->gamificationService->getUserStats($user);
        
        return response()->json($stats);
    }
}
