<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WasteReport;
use App\Models\Achievement;
use App\Models\Reward;
use App\Models\UserAchievement;
use App\Models\RewardRedemption;
use App\Models\PointTransaction;
use Illuminate\Http\Request;

class GamificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $stats = [
            'total_points' => $user->points ?? 0,
            'reports_count' => WasteReport::where('resident_id', $user->id)->count(),
            'achievements_count' => UserAchievement::where('user_id', $user->id)->count(),
            'current_level' => $this->calculateLevel($user->points ?? 0),
            'next_level_points' => $this->getNextLevelPoints($user->points ?? 0)
        ];

        $achievements = Achievement::with(['userAchievements' => function($q) use ($user) {
            $q->where('user_id', $user->id);
        }])->get();

        $recentTransactions = PointTransaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('resident.gamification.index', compact('stats', 'achievements', 'recentTransactions'));
    }

    public function rewards()
    {
        $user = auth()->user();
        
        $rewards = Reward::where('is_active', true)
            ->where('points_required', '<=', $user->points ?? 0)
            ->get();

        $redemptionHistory = RewardRedemption::where('user_id', $user->id)
            ->with('reward')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('resident.gamification.rewards', compact('rewards', 'redemptionHistory'));
    }

    public function redeemReward(Request $request, Reward $reward)
    {
        $user = auth()->user();

        if (($user->points ?? 0) < $reward->points_required) {
            return response()->json(['error' => 'Insufficient points'], 400);
        }

        if (!$reward->is_active) {
            return response()->json(['error' => 'Reward not available'], 400);
        }

        // Create redemption record
        $redemption = RewardRedemption::create([
            'user_id' => $user->id,
            'reward_id' => $reward->id,
            'points_used' => $reward->points_required,
            'status' => 'pending'
        ]);

        // Deduct points
        $user->decrement('points', $reward->points_required);

        // Create point transaction
        PointTransaction::create([
            'user_id' => $user->id,
            'points' => -$reward->points_required,
            'type' => 'redemption',
            'description' => "Redeemed: {$reward->name}",
            'reference_id' => $redemption->id,
            'reference_type' => 'App\Models\RewardRedemption'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reward redeemed successfully',
            'new_balance' => $user->fresh()->points
        ]);
    }

    public function apiStats()
    {
        $user = auth()->user();
        
        return response()->json([
            'total_points' => $user->points ?? 0,
            'reports_count' => WasteReport::where('resident_id', $user->id)->count(),
            'achievements_count' => UserAchievement::where('user_id', $user->id)->count(),
            'current_level' => $this->calculateLevel($user->points ?? 0),
            'next_level_points' => $this->getNextLevelPoints($user->points ?? 0)
        ]);
    }

    private function calculateLevel($points)
    {
        if ($points < 100) return 1;
        if ($points < 250) return 2;
        if ($points < 500) return 3;
        if ($points < 1000) return 4;
        return 5;
    }

    private function getNextLevelPoints($points)
    {
        if ($points < 100) return 100;
        if ($points < 250) return 250;
        if ($points < 500) return 500;
        if ($points < 1000) return 1000;
        return null;
    }
}
