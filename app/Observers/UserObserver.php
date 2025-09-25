<?php

namespace App\Observers;

use App\Models\User;
use App\Services\ActivityLogService;

class UserObserver
{
    protected $activityLogger;

    public function __construct(ActivityLogService $activityLogger)
    {
        $this->activityLogger = $activityLogger;
    }

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $this->activityLogger->logModelChange(
            'create',
            $user,
            null,
            $this->sanitizeUserData($user->toArray())
        );
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        $changes = $user->getChanges();
        $original = $user->getOriginal();
        
        // Log important updates with higher severity
        $severity = 'low';
        if (array_key_exists('status', $changes) || array_key_exists('email', $changes)) {
            $severity = 'medium';
        }

        $this->activityLogger->logModelChange(
            'update',
            $user,
            $this->sanitizeUserData($original),
            $this->sanitizeUserData($changes)
        );
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        $this->activityLogger->logModelChange(
            'delete',
            $user,
            $this->sanitizeUserData($user->toArray()),
            null
        );
    }

    /**
     * Sanitize user data to remove sensitive information
     */
    private function sanitizeUserData(array $data): array
    {
        $sensitiveFields = ['password', 'remember_token', 'email_verified_at'];
        
        foreach ($sensitiveFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = '***REDACTED***';
            }
        }
        
        return $data;
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
