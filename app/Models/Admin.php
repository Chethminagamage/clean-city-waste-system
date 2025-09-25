<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

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
        'is_active',
        'failed_login_attempts', // ✅ Security: Failed login tracking
        'locked_until',         // ✅ Security: Account lockout timestamp
    ];

    protected $hidden = ['password', 'remember_token', 'two_factor_secret'];

    protected $casts = [
        'notification_preferences' => 'array',
        'two_factor_enabled' => 'boolean',
        'two_factor_recovery_codes' => 'array',
        'two_factor_confirmed_at' => 'datetime',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'locked_until' => 'datetime', // ✅ Security: Cast lockout timestamp
    ];

    /**
     * Basic accessor: Check if account has a lock timestamp set
     * Business logic is handled in AuthService
     */
    public function hasLockTimestamp(): bool
    {
        return !is_null($this->locked_until);
    }

    /**
     * Basic accessor: Get the lock timestamp
     */
    public function getLockTimestamp()
    {
        return $this->locked_until;
    }

    /**
     * Generate a new Two-Factor Authentication secret
     */
    public function generateTwoFactorSecret(): string
    {
        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();
        
        $this->two_factor_secret = $secret;
        $this->save();
        
        return $secret;
    }

    /**
     * Get the Two-Factor Authentication QR Code URL
     */
    public function getTwoFactorQrCodeUrl(): string
    {
        if (!$this->two_factor_secret) {
            $this->generateTwoFactorSecret();
        }

        $google2fa = new Google2FA();
        return $google2fa->getQRCodeUrl(
            config('app.name', 'Clean City Admin'),
            $this->email,
            $this->two_factor_secret
        );
    }

    /**
     * Get the Two-Factor Authentication QR Code as SVG
     */
    public function getTwoFactorQrCodeSvg(): string
    {
        $url = $this->getTwoFactorQrCodeUrl();
        
        $renderer = new ImageRenderer(
            new RendererStyle(200),
            new SvgImageBackEnd()
        );
        
        $writer = new Writer($renderer);
        return $writer->writeString($url);
    }

    /**
     * Verify a Two-Factor Authentication code
     */
    public function verifyTwoFactorCode(string $code): bool
    {
        if (!$this->two_factor_secret || !$this->two_factor_enabled) {
            return false;
        }

        $google2fa = new Google2FA();
        return $google2fa->verifyKey($this->two_factor_secret, $code);
    }

    /**
     * Enable Two-Factor Authentication
     */
    public function enableTwoFactor(): void
    {
        $this->two_factor_enabled = true;
        $this->two_factor_confirmed_at = now();
        $this->save();
    }

    /**
     * Disable Two-Factor Authentication
     */
    public function disableTwoFactor(): void
    {
        $this->two_factor_enabled = false;
        $this->two_factor_secret = null;
        $this->two_factor_confirmed_at = null;
        $this->two_factor_recovery_codes = null;
        $this->save();
    }

    /**
     * Generate recovery codes for Two-Factor Authentication
     */
    public function generateRecoveryCodes(): array
    {
        $codes = [];
        for ($i = 0; $i < 8; $i++) {
            $codes[] = strtoupper(substr(md5(random_bytes(32)), 0, 8));
        }
        
        $this->two_factor_recovery_codes = $codes;
        $this->save();
        
        return $codes;
    }

    /**
     * Check if admin has Two-Factor Authentication enabled
     */
    public function hasTwoFactorEnabled(): bool
    {
        return $this->two_factor_enabled && !is_null($this->two_factor_secret);
    }
}
