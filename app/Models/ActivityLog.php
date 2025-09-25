<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Carbon\Carbon;

class ActivityLog extends Model
{
    protected $fillable = [
        'actor_type',
        'actor_id',
        'actor_email',
        'event_type',
        'activity_category',
        'activity_description',
        'subject_type',
        'subject_id',
        'method',
        'url',
        'route_name',
        'request_data',
        'ip_address',
        'user_agent',
        'session_id',
        'old_values',
        'new_values',
        'status',
        'severity',
        'notes',
        'additional_data'
    ];

    protected $casts = [
        'request_data' => 'array',
        'old_values' => 'array',
        'new_values' => 'array',
        'additional_data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Constants for activity categories
    const CATEGORY_AUTHENTICATION = 'authentication';
    const CATEGORY_REPORT_MANAGEMENT = 'report_management';
    const CATEGORY_USER_MANAGEMENT = 'user_management';
    const CATEGORY_COLLECTOR_MANAGEMENT = 'collector_management';
    const CATEGORY_ADMIN_ACTION = 'admin_action';
    const CATEGORY_SECURITY = 'security';
    const CATEGORY_SYSTEM = 'system';
    const CATEGORY_DATA_EXPORT = 'data_export';

    // Constants for event types
    const EVENT_LOGIN = 'login';
    const EVENT_LOGOUT = 'logout';
    const EVENT_FAILED_LOGIN = 'failed_login';
    const EVENT_PASSWORD_RESET = 'password_reset';
    const EVENT_PASSWORD_CHANGE = 'password_change';
    const EVENT_TWO_FACTOR_VERIFY = '2fa_verify';
    const EVENT_TWO_FACTOR_FAILED = '2fa_failed';
    const EVENT_ACCOUNT_LOCKED = 'account_locked';
    const EVENT_CREATE = 'create';
    const EVENT_UPDATE = 'update';
    const EVENT_DELETE = 'delete';
    const EVENT_VIEW = 'view';
    const EVENT_EXPORT = 'export';
    const EVENT_ASSIGN = 'assign';
    const EVENT_UNASSIGN = 'unassign';
    const EVENT_STATUS_CHANGE = 'status_change';

    // Constants for severity levels
    const SEVERITY_LOW = 'low';
    const SEVERITY_MEDIUM = 'medium';
    const SEVERITY_HIGH = 'high';
    const SEVERITY_CRITICAL = 'critical';

    // Constants for status
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';
    const STATUS_ERROR = 'error';

    /**
     * Get the actor (user, admin, collector) for this activity
     */
    public function actor(): MorphTo
    {
        return $this->morphTo('actor');
    }

    /**
     * Get the subject (model) that this activity relates to
     */
    public function subject(): MorphTo
    {
        return $this->morphTo('subject');
    }

    /**
     * Scope for filtering by actor type
     */
    public function scopeByActorType($query, $actorType)
    {
        return $query->where('actor_type', $actorType);
    }

    /**
     * Scope for filtering by event type
     */
    public function scopeByEventType($query, $eventType)
    {
        return $query->where('event_type', $eventType);
    }

    /**
     * Scope for filtering by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('activity_category', $category);
    }

    /**
     * Scope for filtering by severity
     */
    public function scopeBySeverity($query, $severity)
    {
        return $query->where('severity', $severity);
    }

    /**
     * Scope for filtering by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope for recent activities (last 24 hours)
     */
    public function scopeRecent($query)
    {
        return $query->where('created_at', '>=', Carbon::now()->subDay());
    }

    /**
     * Scope for security-related activities
     */
    public function scopeSecurityEvents($query)
    {
        return $query->whereIn('activity_category', [
            self::CATEGORY_AUTHENTICATION,
            self::CATEGORY_SECURITY
        ])->orWhereIn('event_type', [
            self::EVENT_FAILED_LOGIN,
            self::EVENT_ACCOUNT_LOCKED,
            self::EVENT_TWO_FACTOR_FAILED
        ]);
    }

    /**
     * Scope for critical activities
     */
    public function scopeCritical($query)
    {
        return $query->where('severity', self::SEVERITY_CRITICAL);
    }

    /**
     * Get human-readable activity description
     */
    public function getFormattedDescriptionAttribute()
    {
        $actor = $this->actor_email ?? "Unknown";
        return "{$actor} {$this->activity_description}";
    }

    /**
     * Get IP location (if needed for enhanced security)
     */
    public function getIpLocationAttribute()
    {
        // This could be enhanced with IP geolocation service
        return $this->ip_address;
    }

    /**
     * Check if activity is suspicious
     */
    public function isSuspicious()
    {
        return in_array($this->severity, [self::SEVERITY_HIGH, self::SEVERITY_CRITICAL]) ||
               in_array($this->event_type, [
                   self::EVENT_FAILED_LOGIN,
                   self::EVENT_ACCOUNT_LOCKED,
                   self::EVENT_TWO_FACTOR_FAILED
               ]);
    }

    /**
     * Static method to log activity
     */
    public static function logActivity($data)
    {
        return self::create([
            'actor_type' => $data['actor_type'] ?? null,
            'actor_id' => $data['actor_id'] ?? null,
            'actor_email' => $data['actor_email'] ?? null,
            'event_type' => $data['event_type'],
            'activity_category' => $data['activity_category'],
            'activity_description' => $data['activity_description'],
            'subject_type' => $data['subject_type'] ?? null,
            'subject_id' => $data['subject_id'] ?? null,
            'method' => $data['method'] ?? null,
            'url' => $data['url'] ?? null,
            'route_name' => $data['route_name'] ?? null,
            'request_data' => $data['request_data'] ?? null,
            'ip_address' => $data['ip_address'] ?? request()->ip(),
            'user_agent' => $data['user_agent'] ?? request()->userAgent(),
            'session_id' => $data['session_id'] ?? session()->getId(),
            'old_values' => $data['old_values'] ?? null,
            'new_values' => $data['new_values'] ?? null,
            'status' => $data['status'] ?? self::STATUS_SUCCESS,
            'severity' => $data['severity'] ?? self::SEVERITY_LOW,
            'notes' => $data['notes'] ?? null,
            'additional_data' => $data['additional_data'] ?? null,
        ]);
    }
}
