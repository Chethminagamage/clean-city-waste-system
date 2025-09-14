<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Reward extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'image',
        'cost_points',
        'type',
        'reward_data',
        'quantity',
        'is_active',
        'expires_at'
    ];

    protected $casts = [
        'reward_data' => 'array',
        'is_active' => 'boolean',
        'expires_at' => 'date'
    ];

    public function redemptions(): HasMany
    {
        return $this->hasMany(RewardRedemption::class);
    }

    public function isAvailable()
    {
        if (!$this->is_active) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        if ($this->quantity !== null && $this->redemptions()->count() >= $this->quantity) return false;
        
        return true;
    }

    public function getRemainingQuantity()
    {
        if ($this->quantity === null) return null;
        return $this->quantity - $this->redemptions()->count();
    }
}
