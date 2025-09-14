<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Achievement extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'category',
        'points_reward',
        'requirements',
        'is_repeatable',
        'rarity',
        'is_active'
    ];

    protected $casts = [
        'requirements' => 'array',
        'is_repeatable' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function userAchievements(): HasMany
    {
        return $this->hasMany(UserAchievement::class);
    }

    public function isEarnedByUser($userId)
    {
        return $this->userAchievements()
            ->where('user_id', $userId)
            ->exists();
    }

    public function getRarityColor()
    {
        return match($this->rarity) {
            'common' => 'gray',
            'rare' => 'blue',
            'epic' => 'purple',
            'legendary' => 'yellow',
            default => 'gray'
        };
    }
}
