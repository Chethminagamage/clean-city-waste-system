<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $guard = 'admin';

    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'phone', 
        'position', 
        'department', 
        'profile_photo', 
        'bio', 
        'notification_preferences', 
        'timezone', 
        'two_factor_enabled', 
        'two_factor_secret', 
        'last_login_at', 
        'last_login_ip', 
        'is_active'
    ];

    protected $hidden = ['password', 'remember_token', 'two_factor_secret'];

    protected $casts = [
        'notification_preferences' => 'array',
        'two_factor_enabled' => 'boolean',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
