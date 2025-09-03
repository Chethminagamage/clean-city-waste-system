<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserGamification extends Model
{
    protected $table = 'user_gamification';
    
    protected $fillable = [
        'user_id',
        'total_points',
        'current_level',
        'points_to_next_level',
        'current_rank',
        'weekly_points',
        'monthly_points',
        'last_weekly_reset',
        'last_monthly_reset'
    ];

    protected $casts = [
        'last_weekly_reset' => 'date',
        'last_monthly_reset' => 'date'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Level system constants
    public static $levels = [
        1 => ['name' => 'Eco Newbie', 'points' => 0],
        2 => ['name' => 'Green Starter', 'points' => 100],
        3 => ['name' => 'Eco Enthusiast', 'points' => 300],
        4 => ['name' => 'Green Guardian', 'points' => 600],
        5 => ['name' => 'Eco Champion', 'points' => 1000],
        6 => ['name' => 'Sustainability Hero', 'points' => 1500],
        7 => ['name' => 'Environmental Master', 'points' => 2200],
        8 => ['name' => 'Planet Protector', 'points' => 3000],
        9 => ['name' => 'Eco Legend', 'points' => 4000],
        10 => ['name' => 'Earth Savior', 'points' => 5500]
    ];

    public function calculateLevel()
    {
        $currentLevel = 1;
        foreach (self::$levels as $level => $data) {
            if ($this->total_points >= $data['points']) {
                $currentLevel = $level;
            }
        }
        return $currentLevel;
    }

    public function getNextLevelPoints()
    {
        $currentLevel = $this->calculateLevel();
        $nextLevel = $currentLevel + 1;
        
        if (isset(self::$levels[$nextLevel])) {
            return self::$levels[$nextLevel]['points'] - $this->total_points;
        }
        
        return 0; // Max level reached
    }

    public function updateLevel()
    {
        $newLevel = $this->calculateLevel();
        $this->current_level = $newLevel;
        $this->current_rank = self::$levels[$newLevel]['name'];
        $this->points_to_next_level = $this->getNextLevelPoints();
        $this->save();
    }
}
