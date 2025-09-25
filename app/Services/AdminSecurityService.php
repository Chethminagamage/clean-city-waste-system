<?php

namespace App\Services;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Professional Admin Security Service
 * Handles all admin security operations including 2FA, IP filtering, session management
 */
class AdminSecurityService
{
    /**
     * Event severity levels
     */
    const SEVERITY_LOW = 'low';
    const SEVERITY_MEDIUM = 'medium';
    const SEVERITY_HIGH = 'high';
    const SEVERITY_CRITICAL = 'critical';

    /**
     * Security event types
     */
    const EVENT_LOGIN_SUCCESS = 'login_success';
    const EVENT_LOGIN_FAILED = 'login_failed';
    const EVENT_MULTIPLE_FAILED_LOGINS = 'multiple_failed_logins';
    const EVENT_UNAUTHORIZED_IP = 'unauthorized_ip_access';
    const EVENT_PASSWORD_CHANGED = 'password_changed';
    const EVENT_2FA_DISABLED = '2fa_disabled';
    const EVENT_2FA_ENABLED = '2fa_enabled';
    const EVENT_SUSPICIOUS_ACTIVITY = 'suspicious_activity';
    const EVENT_SESSION_HIJACK = 'session_hijack_attempt';
    const EVENT_ACCOUNT_LOCKED = 'account_locked';

    /**
     * Allowed IP addresses for admin access
     */
    protected array $allowedIps = [
        '127.0.0.1',     // Localhost
        '::1',           // IPv6 localhost
        // Add your office/home IPs here
    ];

    /**
     * Log a security event
     */
    public function logSecurityEvent(string $eventType, ?int $adminId = null, array $details = []): void
    {
        $severity = $this->getEventSeverity($eventType);
        $request = request();

        $logData = [
            'admin_id' => $adminId,
            'event_type' => $eventType,
            'severity' => $severity,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()->toISOString(),
            'details' => $details,
        ];

        // Log to Laravel log
        Log::channel('security')->info("Admin Security Event: {$eventType}", $logData);

        // Store in database (if table exists)
        $this->storeSecurityLog($logData);

        // Send alerts for high-severity events
        if (in_array($severity, [self::SEVERITY_HIGH, self::SEVERITY_CRITICAL])) {
            $this->sendSecurityAlert($eventType, $logData);
        }
    }

    /**
     * Check if IP address is allowed for admin access
     */
    public function isIpAllowed(string $ip): bool
    {
        // Always allow local development
        if (app()->environment('local')) {
            return true;
        }

        // Check against whitelist
        return in_array($ip, $this->allowedIps) || 
               $this->isIpInWhitelist($ip);
    }

    /**
     * Track successful admin login
     */
    public function trackSuccessfulLogin(Admin $admin, Request $request): void
    {
        $admin->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
            'failed_login_attempts' => 0, // Reset failed attempts
        ]);

        $this->logSecurityEvent(self::EVENT_LOGIN_SUCCESS, $admin->id, [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }

    /**
     * Track failed admin login attempt
     */
    public function trackFailedLogin(?Admin $admin, Request $request): void
    {
        if ($admin) {
            $attempts = $admin->failed_login_attempts + 1;
            $admin->increment('failed_login_attempts');

            // Lock account after 5 failed attempts for 30 minutes
            if ($attempts >= 5) {
                $admin->update(['locked_until' => now()->addMinutes(30)]);
                
                $this->logSecurityEvent(self::EVENT_ACCOUNT_LOCKED, $admin->id, [
                    'attempts' => $attempts,
                    'locked_until' => $admin->locked_until,
                ]);
            } else {
                $this->logSecurityEvent(self::EVENT_LOGIN_FAILED, $admin->id, [
                    'attempts' => $attempts,
                    'ip' => $request->ip(),
                ]);
            }
        } else {
            $this->logSecurityEvent(self::EVENT_LOGIN_FAILED, null, [
                'email' => $request->input('email'),
                'ip' => $request->ip(),
            ]);
        }
    }

    /**
     * Validate Two-Factor Authentication
     */
    public function validateTwoFactor(Admin $admin, string $code): bool
    {
        if (!$admin->hasTwoFactorEnabled()) {
            return true; // 2FA not enabled for this admin
        }

        $isValid = $admin->verifyTwoFactorCode($code);
        
        if (!$isValid) {
            $this->logSecurityEvent(self::EVENT_LOGIN_FAILED, $admin->id, [
                'reason' => '2fa_failed',
                'ip' => request()->ip(),
            ]);
        }

        return $isValid;
    }

    /**
     * Enable Two-Factor Authentication for admin
     */
    public function enableTwoFactor(Admin $admin, string $verificationCode): bool
    {
        if (!$admin->two_factor_secret) {
            $admin->generateTwoFactorSecret();
        }

        if ($admin->verifyTwoFactorCode($verificationCode)) {
            $admin->enableTwoFactor();
            $admin->generateRecoveryCodes();
            
            $this->logSecurityEvent(self::EVENT_2FA_ENABLED, $admin->id);
            return true;
        }

        return false;
    }

    /**
     * Disable Two-Factor Authentication for admin
     */
    public function disableTwoFactor(Admin $admin): void
    {
        $admin->disableTwoFactor();
        
        $this->logSecurityEvent(self::EVENT_2FA_DISABLED, $admin->id, [
            'warning' => 'Admin account security reduced'
        ]);
    }

    /**
     * Check for suspicious login patterns
     */
    public function detectSuspiciousActivity(Admin $admin, Request $request): bool
    {
        $suspicious = false;

        // Check for rapid location changes
        if ($admin->last_login_ip && $admin->last_login_ip !== $request->ip()) {
            if ($admin->last_login_at && $admin->last_login_at->diffInMinutes(now()) < 5) {
                $suspicious = true;
                $this->logSecurityEvent(self::EVENT_SUSPICIOUS_ACTIVITY, $admin->id, [
                    'reason' => 'rapid_location_change',
                    'previous_ip' => $admin->last_login_ip,
                    'current_ip' => $request->ip(),
                ]);
            }
        }

        // Check for unusual user agent
        // Add more sophisticated detection logic here

        return $suspicious;
    }

    /**
     * Get event severity level
     */
    private function getEventSeverity(string $eventType): string
    {
        $severityMap = [
            self::EVENT_LOGIN_SUCCESS => self::SEVERITY_LOW,
            self::EVENT_LOGIN_FAILED => self::SEVERITY_MEDIUM,
            self::EVENT_MULTIPLE_FAILED_LOGINS => self::SEVERITY_HIGH,
            self::EVENT_UNAUTHORIZED_IP => self::SEVERITY_CRITICAL,
            self::EVENT_PASSWORD_CHANGED => self::SEVERITY_MEDIUM,
            self::EVENT_2FA_DISABLED => self::SEVERITY_HIGH,
            self::EVENT_2FA_ENABLED => self::SEVERITY_LOW,
            self::EVENT_SUSPICIOUS_ACTIVITY => self::SEVERITY_CRITICAL,
            self::EVENT_SESSION_HIJACK => self::SEVERITY_CRITICAL,
            self::EVENT_ACCOUNT_LOCKED => self::SEVERITY_HIGH,
        ];

        return $severityMap[$eventType] ?? self::SEVERITY_MEDIUM;
    }

    /**
     * Check if IP is in database whitelist
     */
    private function isIpInWhitelist(string $ip): bool
    {
        // This would check against a database table of allowed IPs
        // For now, return false to use the hardcoded list above
        return false;
    }

    /**
     * Store security log in database
     */
    private function storeSecurityLog(array $logData): void
    {
        try {
            // This would store in a security_logs table
            // For now, we just use Laravel's logging system
        } catch (\Exception $e) {
            Log::error('Failed to store security log: ' . $e->getMessage());
        }
    }

    /**
     * Send security alert notifications
     */
    private function sendSecurityAlert(string $eventType, array $logData): void
    {
        try {
            // Send email notifications to admin team
            // Send Slack/Discord notifications  
            // Send SMS for critical events
            
            Log::warning("HIGH SEVERITY ADMIN SECURITY EVENT", [
                'event' => $eventType,
                'data' => $logData
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to send security alert: ' . $e->getMessage());
        }
    }

    /**
     * Get security configuration
     */
    public function getSecurityConfig(): array
    {
        return [
            'max_login_attempts' => 5,
            'lockout_duration_minutes' => 30,
            'session_timeout_minutes' => 30,
            'require_2fa' => env('ADMIN_REQUIRE_2FA', false),
            'allowed_ips' => $this->allowedIps,
        ];
    }
}