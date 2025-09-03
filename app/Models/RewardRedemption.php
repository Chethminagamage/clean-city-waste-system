<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class RewardRedemption extends Model
{
    protected $fillable = [
        'user_id',
        'reward_id',
        'points_spent',
        'status',
        'redemption_code',
        'redeemed_at',
        'expires_at',
        'redemption_data'
    ];

    protected $casts = [
        'redeemed_at' => 'datetime',
        'expires_at' => 'datetime',
        'redemption_data' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($redemption) {
            $redemption->redemption_code = 'CC-' . strtoupper(Str::random(8));
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reward(): BelongsTo
    {
        return $this->belongsTo(Reward::class);
    }

    public function isActive()
    {
        return $this->status === 'completed' && 
               (!$this->expires_at || $this->expires_at->isFuture());
    }
}
