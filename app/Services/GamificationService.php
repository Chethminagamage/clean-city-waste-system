<?php

namespace App\Services;

use App\Models\User;
use App\Models\Achievement;
use App\Models\UserAchievement;
use App\Models\PointTransaction;
use App\Models\UserGamification;
use App\Models\WasteReport;
use App\Models\Feedback;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GamificationService
{
    // Point values for different actions
    const POINTS = [
        'report_submitted' => 10,
        'report_collected' => 5,
        'feedback_given' => 3,
        'daily_login' => 2,
        'first_report' => 25,
        'weekly_streak' => 50,
        'monthly_streak' => 100,
        'urgent_report' => 15,
        'proper_segregation' => 8
    ];

    public function awardPoints(User $user, string $source, int $points = null, string $description = null, array $metadata = [])
    {
        try {
            $points = $points ?? self::POINTS[$source] ?? 0;
            $description = $description ?? $this->getDefaultDescription($source, $points);

            // Create point transaction
            PointTransaction::create([
                'user_id' => $user->id,
                'type' => 'earned',
                'points' => $points,
                'source' => $source,
                'description' => $description,
                'metadata' => $metadata
            ]);

            // Update user gamification
            $gamification = $this->getOrCreateUserGamification($user);
            $gamification->total_points += $points;
            $gamification->weekly_points += $points;
            $gamification->monthly_points += $points;
            $gamification->updateLevel();

            // Check for achievements
            $this->checkAchievements($user, $source, $metadata);

            return true;
        } catch (\Exception $e) {
            Log::error('Error awarding points: ' . $e->getMessage());
            return false;
        }
    }

    public function spendPoints(User $user, int $points, string $description, array $metadata = [])
    {
        $gamification = $this->getOrCreateUserGamification($user);
        
        if ($gamification->total_points < $points) {
            return false; // Insufficient points
        }

        PointTransaction::create([
            'user_id' => $user->id,
            'type' => 'spent',
            'points' => $points,
            'source' => 'reward_redemption',
            'description' => $description,
            'metadata' => $metadata
        ]);

        $gamification->total_points -= $points;
        $gamification->save();

        return true;
    }

    public function checkAchievements(User $user, string $trigger = null, array $context = [])
    {
        $achievements = Achievement::where('is_active', true)->get();
        
        foreach ($achievements as $achievement) {
            // Skip if user already has this achievement and it's not repeatable
            if (!$achievement->is_repeatable && $achievement->isEarnedByUser($user->id)) {
                continue;
            }

            if ($this->checkAchievementRequirements($user, $achievement, $trigger, $context)) {
                $this->awardAchievement($user, $achievement);
            }
        }
    }

    private function checkAchievementRequirements(User $user, Achievement $achievement, string $trigger = null, array $context = []): bool
    {
        $requirements = $achievement->requirements;
        
        switch ($achievement->slug) {
            case 'first-report':
                return WasteReport::where('resident_id', $user->id)->count() >= 1;
                
            case 'report-master':
                return WasteReport::where('resident_id', $user->id)->count() >= 10;
                
            case 'eco-warrior':
                return WasteReport::where('resident_id', $user->id)->count() >= 50;
                
            case 'feedback-champion':
                return Feedback::where('user_id', $user->id)->count() >= 5;
                
            case 'punctual-reporter':
                $reports = WasteReport::where('resident_id', $user->id)
                    ->where('created_at', '>=', now()->subDays(7))
                    ->count();
                return $reports >= 3;
                
            case 'consistency-king':
                return $this->checkConsistentReporting($user, 30); // 30 days
                
            case 'speed-demon':
                return $trigger === 'report_submitted' && 
                       isset($context['response_time']) && 
                       $context['response_time'] < 60; // Less than 1 minute
                
            case 'community-hero':
                return WasteReport::where('resident_id', $user->id)
                    ->where('is_urgent', true)
                    ->count() >= 5;
                
            case 'points-collector':
                $gamification = $this->getOrCreateUserGamification($user);
                return $gamification->total_points >= 500;
                
            case 'level-climber':
                $gamification = $this->getOrCreateUserGamification($user);
                return $gamification->current_level >= 5;
                
            default:
                return false;
        }
    }

    private function checkConsistentReporting(User $user, int $days): bool
    {
        $startDate = now()->subDays($days);
        $reports = WasteReport::where('resident_id', $user->id)
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as report_date')
            ->groupBy('report_date')
            ->pluck('report_date')
            ->toArray();
            
        return count($reports) >= ($days * 0.7); // 70% consistency
    }

    private function awardAchievement(User $user, Achievement $achievement)
    {
        UserAchievement::create([
            'user_id' => $user->id,
            'achievement_id' => $achievement->id,
            'earned_points' => $achievement->points_reward,
            'earned_at' => now()
        ]);

        // Award bonus points
        $this->awardPoints(
            $user, 
            'achievement_earned', 
            $achievement->points_reward,
            "Achievement unlocked: {$achievement->name}",
            ['achievement_id' => $achievement->id]
        );
    }

    public function getLeaderboard($type = 'weekly', $area = null, $limit = 10)
    {
        $query = User::select('users.*')
            ->join('user_gamification', 'users.id', '=', 'user_gamification.user_id')
            ->where('users.role', 'resident');

        switch ($type) {
            case 'weekly':
                $query->orderBy('user_gamification.weekly_points', 'desc');
                break;
            case 'monthly':
                $query->orderBy('user_gamification.monthly_points', 'desc');
                break;
            case 'all_time':
            default:
                $query->orderBy('user_gamification.total_points', 'desc');
                break;
        }

        if ($area) {
            $query->where('users.area', $area);
        }

        return $query->with('gamification')->limit($limit)->get();
    }

    public function resetWeeklyPoints()
    {
        UserGamification::query()->update([
            'weekly_points' => 0,
            'last_weekly_reset' => now()->toDateString()
        ]);
    }

    public function resetMonthlyPoints()
    {
        UserGamification::query()->update([
            'monthly_points' => 0,
            'last_monthly_reset' => now()->toDateString()
        ]);
    }

    private function getOrCreateUserGamification(User $user): UserGamification
    {
        return $user->gamification ?? $user->gamification()->create([]);
    }

    private function getDefaultDescription(string $source, int $points): string
    {
        return match($source) {
            'report_submitted' => "Earned {$points} points for submitting a waste report",
            'report_collected' => "Earned {$points} points for completed waste collection",
            'feedback_given' => "Earned {$points} points for providing feedback",
            'daily_login' => "Earned {$points} points for daily login",
            'achievement_earned' => "Earned {$points} points for unlocking an achievement",
            default => "Earned {$points} points"
        };
    }

    public function getUserStats(User $user): array
    {
        $user->load('gamification', 'achievements');
        $gamification = $this->getOrCreateUserGamification($user);
        
        return [
            'total_points' => $gamification->total_points,
            'current_level' => $gamification->current_level,
            'current_rank' => $gamification->current_rank,
            'points_to_next_level' => $gamification->points_to_next_level,
            'weekly_points' => $gamification->weekly_points,
            'monthly_points' => $gamification->monthly_points,
            'achievements_count' => $user->achievements()->count(),
            'weekly_rank' => $this->getUserRank($user, 'weekly'),
            'monthly_rank' => $this->getUserRank($user, 'monthly'),
            'all_time_rank' => $this->getUserRank($user, 'all_time')
        ];
    }

    private function getUserRank(User $user, string $type): int
    {
        $pointColumn = match($type) {
            'weekly' => 'weekly_points',
            'monthly' => 'monthly_points',
            default => 'total_points'
        };

        // Load the gamification relationship if not already loaded
        if (!$user->relationLoaded('gamification')) {
            $user->load('gamification');
        }
        
        $userPoints = $user->gamification?->$pointColumn ?? 0;
        
        return UserGamification::where($pointColumn, '>', $userPoints)->count() + 1;
    }
}
