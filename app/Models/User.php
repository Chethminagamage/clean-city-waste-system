<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Mail\CustomResetPasswordMail;
use App\Mail\CollectorResetPasswordMail;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'two_factor_code',
        'two_factor_expires_at',
        'two_factor_enabled',
        'phone',         // ✅ Add if your profile form includes this
        'profile_image', // ✅ Optional for profile picture upload
        'contact',
        'location',
        'latitude',
        'longitude',
        'theme_preference', // Add theme preference
        'last_login_at',     // Add for daily login tracking
        'google_id',        // ✅ Google ID for Google signup
        'avatar',           // ✅ Google profile picture
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_code',
        'two_factor_expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'two_factor_expires_at' => 'datetime',
            'two_factor_enabled' => 'boolean',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Get full path of the profile image.
     */
    public function getProfileImageUrlAttribute()
    {
        return $this->profile_image
            ? asset('storage/' . $this->profile_image)
            : "https://ui-avatars.com/api/?name=" . urlencode($this->name) . "&background=059669&color=ffffff&size=256";
    }

    public function sendPasswordResetNotification($token)
    {
        $resetUrl = url(route('password.reset', [
            'token' => $token,
            'email' => $this->getEmailForPasswordReset(),
        ], false));

        // Check if the user is a collector and send appropriate email
        if ($this->role === 'collector') {
            $resetUrl = url(route('collector.password.reset', [
                'token' => $token,
                'email' => $this->getEmailForPasswordReset(),
            ], false));
            
            Mail::to($this->email)->send(new CollectorResetPasswordMail($resetUrl, $this->name));
        } else {
            Mail::to($this->email)->send(new CustomResetPasswordMail($resetUrl, $this->name));
        }
    }

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\CustomEmailVerificationNotification);
    }

    public function area() { return $this->belongsTo(\App\Models\Area::class); }

    /**
     * Relationship: User has many waste reports as collector
     */
    public function wasteReports()
    {
        return $this->hasMany(WasteReport::class, 'collector_id');
    }

    /**
     * Relationship: User has many waste reports as resident
     */
    public function reportedWastes()
    {
        return $this->hasMany(WasteReport::class, 'resident_id');
    }

    /**
     * Gamification relationships
     */
    public function gamification()
    {
        return $this->hasOne(UserGamification::class);
    }

    public function achievements()
    {
        return $this->hasMany(UserAchievement::class);
    }

    public function pointTransactions()
    {
        return $this->hasMany(PointTransaction::class);
    }

    public function rewardRedemptions()
    {
        return $this->hasMany(RewardRedemption::class);
    }

    /**
     * Get or create user gamification record
     */
    public function getOrCreateGamification()
    {
        if (!$this->gamification) {
            return $this->gamification()->create([]);
        }
        return $this->gamification;
    }
}