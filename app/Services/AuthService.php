<?php

namespace App\Services;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    protected $activityLogger;

    public function __construct(ActivityLogService $activityLogger)
    {
        $this->activityLogger = $activityLogger;
    }
    /**
     * Check if a user account is currently locked
     */
    public function isAccountLocked($user): bool
    {
        return $user->locked_until && now()->lt($user->locked_until);
    }

    /**
     * Increment failed login attempts and lock account if necessary
     */
    public function incrementFailedAttempts($user): void
    {
        $user->increment('failed_login_attempts');
        
        if ($user instanceof Admin) {
            // Lock admin account after 4 failed attempts for 15 minutes
            if ($user->failed_login_attempts >= 4) {
                $user->update([
                    'locked_until' => now()->addMinutes(15)
                ]);
                
                // Log account lockout
                $this->activityLogger->logSecurityEvent(
                    'account_locked',
                    'Admin account was locked after 4 failed login attempts',
                    'critical',
                    $user,
                    ['lockout_duration' => '15 minutes', 'attempts' => $user->failed_login_attempts]
                );
            }
        } else {
            // Progressive lockout for regular users (residents)
            if ($user->failed_login_attempts >= 5) {
                // 5+ failed attempts: Lock for 30 minutes
                $user->update([
                    'locked_until' => now()->addMinutes(30)
                ]);
                
                // Log account lockout
                $this->activityLogger->logSecurityEvent(
                    'account_locked',
                    'User account was locked after 5 failed login attempts',
                    'high',
                    $user,
                    ['lockout_duration' => '30 minutes', 'attempts' => $user->failed_login_attempts]
                );
            } elseif ($user->failed_login_attempts >= 3) {
                // 3-4 failed attempts: Lock for 15 minutes
                $user->update([
                    'locked_until' => now()->addMinutes(15)
                ]);
                
                // Log account lockout
                $this->activityLogger->logSecurityEvent(
                    'account_locked',
                    'User account was locked after 3 failed login attempts',
                    'medium',
                    $user,
                    ['lockout_duration' => '15 minutes', 'attempts' => $user->failed_login_attempts]
                );
            }
        }
        
        // Log the failed attempt
        $this->activityLogger->logAuth(
            'failed_login',
            $user,
            'failed',
            ['attempts' => $user->failed_login_attempts, 'ip_address' => request()->ip()]
        );
    }

    /**
     * Reset failed login attempts after successful authentication
     */
    public function resetFailedAttempts($user): void
    {
        $user->update([
            'failed_login_attempts' => 0,
            'locked_until' => null,
            'last_login_at' => now(),
            'last_login_ip' => request()->ip()
        ]);
        
        // Log successful login
        $this->activityLogger->logAuth(
            'login',
            $user,
            'success',
            ['previous_attempts' => $user->getOriginal('failed_login_attempts')]
        );
    }

    /**
     * Get minutes remaining until account unlock
     */
    public function getMinutesUntilUnlock($user): int
    {
        if (!$this->isAccountLocked($user)) {
            return 0;
        }
        
        return now()->diffInMinutes($user->locked_until);
    }

    /**
     * Validate user credentials
     */
    public function validateCredentials($user, string $password): bool
    {
        return Hash::check($password, $user->password);
    }

    /**
     * Check if user has the required role
     */
    public function hasRequiredRole($user, string $requiredRole): bool
    {
        return $user->role === $requiredRole;
    }

    /**
     * Check if user account is blocked by admin
     */
    public function isAccountBlocked($user): bool
    {
        return $user->status === 'blocked';
    }

    /**
     * Handle failed login attempt
     */
    public function handleFailedLogin($user): array
    {
        $this->incrementFailedAttempts($user);
        
        $refreshedUser = $user->fresh();
        
        if ($this->isAccountLocked($refreshedUser)) {
            $minutesRemaining = $this->getMinutesUntilUnlock($refreshedUser);
            $lockoutMessage = $this->getLockoutMessage($refreshedUser, $minutesRemaining);
            
            return [
                'locked' => true,
                'minutes' => $minutesRemaining,
                'attempts' => $refreshedUser->failed_login_attempts,
                'message' => $lockoutMessage
            ];
        }
        
        // Provide warning for users approaching lockout
        $warningMessage = $this->getWarningMessage($refreshedUser);
        
        return [
            'locked' => false,
            'attempts' => $refreshedUser->failed_login_attempts,
            'message' => $warningMessage ?: 'Invalid credentials.'
        ];
    }

    /**
     * Get appropriate lockout message based on failed attempts
     */
    private function getLockoutMessage($user, int $minutesRemaining): string
    {
        if ($user instanceof Admin) {
            return "Admin account is locked due to 4 failed login attempts. Try again in {$minutesRemaining} minute(s).";
        }

        if ($user->failed_login_attempts >= 5) {
            return "Account is locked due to 5 failed login attempts. Try again in {$minutesRemaining} minute(s).";
        } elseif ($user->failed_login_attempts >= 3) {
            return "Account is locked due to 3 failed login attempts. Try again in {$minutesRemaining} minute(s).";
        }

        return "Account is locked due to too many failed attempts. Try again in {$minutesRemaining} minute(s).";
    }

    /**
     * Get warning message for users approaching lockout
     */
    private function getWarningMessage($user): ?string
    {
        if ($user instanceof Admin) {
            if ($user->failed_login_attempts === 3) {
                return "Warning: 1 more failed attempt will lock your admin account for 15 minutes.";
            } elseif ($user->failed_login_attempts === 2) {
                return "Warning: 2 more failed attempts will lock your admin account.";
            }
            // Removed warning for 1 failed attempt - will show generic "invalid credentials" message instead
        } else {
            if ($user->failed_login_attempts === 4) {
                return "Warning: 1 more failed attempt will lock your account for 30 minutes.";
            } elseif ($user->failed_login_attempts === 2) {
                return "Warning: 1 more failed attempt will lock your account for 15 minutes.";
            }
            // Removed the warning for 1 failed attempt - will show generic "incorrect credentials" message instead
        }

        return null;
    }

    /**
     * Handle successful login
     */
    public function handleSuccessfulLogin($user): void
    {
        $this->resetFailedAttempts($user);
    }
    
    /**
     * Log user logout
     */
    public function logLogout($user): void
    {
        $this->activityLogger->logAuth('logout', $user, 'success');
    }
    
    /**
     * Log password change
     */
    public function logPasswordChange($user): void
    {
        $this->activityLogger->logAuth(
            'password_change',
            $user,
            'success',
            ['changed_at' => now()]
        );
    }
    
    /**
     * Log password reset
     */
    public function logPasswordReset($user): void
    {
        $this->activityLogger->logAuth(
            'password_reset',
            $user,
            'success',
            ['reset_at' => now()]
        );
    }
    
    /**
     * Log 2FA verification
     */
    public function log2FAVerification($user, $success = true): void
    {
        $this->activityLogger->logAuth(
            $success ? '2fa_verify' : '2fa_failed',
            $user,
            $success ? 'success' : 'failed',
            ['verification_time' => now()]
        );
    }

    /**
     * Validate resident login attempt
     */
    public function validateResidentLogin($user, string $password): array
    {
        // 1. Check if user exists
        if (!$user) {
            return [
                'success' => false,
                'error' => 'email',
                'message' => 'No account found with this email address.'
            ];
        }

        // 2. Check account lock status
        if ($this->isAccountLocked($user)) {
            $minutesRemaining = $this->getMinutesUntilUnlock($user);
            $lockoutMessage = $this->getLockoutMessage($user, $minutesRemaining);
            return [
                'success' => false,
                'error' => 'email',
                'message' => $lockoutMessage
            ];
        }

        // 3. Validate password
        if (!$this->validateCredentials($user, $password)) {
            $failedResult = $this->handleFailedLogin($user);
            return [
                'success' => false,
                'error' => 'email',
                'message' => $failedResult['message']
            ];
        }

        // 4. Check role
        if (!$this->hasRequiredRole($user, 'resident')) {
            return [
                'success' => false,
                'error' => 'email',
                'message' => 'You are not authorized to log in as a resident.'
            ];
        }

        // 5. Check if account is blocked
        if ($this->isAccountBlocked($user)) {
            return [
                'success' => false,
                'error' => 'email',
                'message' => 'Your account has been blocked by the admin.'
            ];
        }

        // 6. Check email verification
        if (!$user->hasVerifiedEmail()) {
            return [
                'success' => false,
                'error' => 'email_verification',
                'user' => $user
            ];
        }

        return [
            'success' => true,
            'user' => $user
        ];
    }

    /**
     * Validate collector login attempt
     */
    public function validateCollectorLogin($user, string $password): array
    {
        // 1. Check if user exists
        if (!$user) {
            return [
                'success' => false,
                'error' => 'email',
                'message' => 'No account found with this email address.'
            ];
        }

        // 2. Check role first
        if (!$this->hasRequiredRole($user, 'collector')) {
            return [
                'success' => false,
                'error' => 'email',
                'message' => 'You are not authorized to log in as a collector.'
            ];
        }

        // 3. Check account lock status
        if ($this->isAccountLocked($user)) {
            $minutesRemaining = $this->getMinutesUntilUnlock($user);
            return [
                'success' => false,
                'error' => 'email',
                'message' => "Account is locked due to too many failed attempts. Try again in {$minutesRemaining} minute(s)."
            ];
        }

        // 4. Validate password
        if (!$this->validateCredentials($user, $password)) {
            $failedResult = $this->handleFailedLogin($user);
            return [
                'success' => false,
                'error' => 'email',
                'message' => $failedResult['locked'] ? $failedResult['message'] : 'Incorrect password.'
            ];
        }

        // 5. Check if account is blocked
        if ($this->isAccountBlocked($user)) {
            return [
                'success' => false,
                'error' => 'email',
                'message' => 'Your account is blocked. Please contact admin.'
            ];
        }

        return [
            'success' => true,
            'user' => $user
        ];
    }

    /**
     * Validate admin login attempt
     */
    public function validateAdminLogin($admin, string $password): array
    {
        // 1. Check if admin exists
        if (!$admin) {
            return [
                'success' => false,
                'error' => 'email',
                'message' => 'No account found with this email address.'
            ];
        }

        // 2. Check account lock status
        if ($this->isAccountLocked($admin)) {
            $minutesRemaining = $this->getMinutesUntilUnlock($admin);
            return [
                'success' => false,
                'error' => 'email',
                'message' => "Account is locked due to too many failed attempts. Try again in {$minutesRemaining} minute(s)."
            ];
        }

        // 3. Validate password
        if (!$this->validateCredentials($admin, $password)) {
            $failedResult = $this->handleFailedLogin($admin);
            return [
                'success' => false,
                'error' => 'email',
                'message' => $failedResult['locked'] ? $failedResult['message'] : 'Invalid credentials.'
            ];
        }

        return [
            'success' => true,
            'admin' => $admin
        ];
    }
}