<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class ActivityLogService
{
    /**
     * Log authentication events
     */
    public function logAuth($eventType, $user = null, $status = ActivityLog::STATUS_SUCCESS, $additionalData = [])
    {
        $severity = $this->determineSeverity($eventType, $status);
        
        return ActivityLog::logActivity([
            'actor_type' => $user ? get_class($user) : null,
            'actor_id' => $user?->id,
            'actor_email' => $user?->email ?? request('email'),
            'event_type' => $eventType,
            'activity_category' => ActivityLog::CATEGORY_AUTHENTICATION,
            'activity_description' => $this->getAuthDescription($eventType, $status),
            'status' => $status,
            'severity' => $severity,
            'method' => request()->method(),
            'url' => request()->fullUrl(),
            'route_name' => request()->route()?->getName(),
            'additional_data' => $additionalData
        ]);
    }

    /**
     * Log data model changes (CRUD operations)
     */
    public function logModelChange($eventType, $model, $oldValues = null, $newValues = null, $user = null)
    {
        $user = $user ?? $this->getCurrentUser();
        
        return ActivityLog::logActivity([
            'actor_type' => $user ? get_class($user) : null,
            'actor_id' => $user?->id,
            'actor_email' => $user?->email,
            'event_type' => $eventType,
            'activity_category' => $this->getCategoryFromModel($model),
            'activity_description' => $this->getModelChangeDescription($eventType, $model),
            'subject_type' => get_class($model),
            'subject_id' => $model->id ?? null,
            'old_values' => $oldValues ? $this->sanitizeData($oldValues) : null,
            'new_values' => $newValues ? $this->sanitizeData($newValues) : null,
            'severity' => ActivityLog::SEVERITY_LOW,
            'method' => request()->method(),
            'url' => request()->fullUrl(),
            'route_name' => request()->route()?->getName(),
        ]);
    }

    /**
     * Log admin actions
     */
    public function logAdminAction($description, $eventType = ActivityLog::EVENT_UPDATE, $subject = null, $severity = ActivityLog::SEVERITY_MEDIUM, $additionalData = [])
    {
        $user = $this->getCurrentUser();
        
        return ActivityLog::logActivity([
            'actor_type' => $user ? get_class($user) : null,
            'actor_id' => $user?->id,
            'actor_email' => $user?->email,
            'event_type' => $eventType,
            'activity_category' => ActivityLog::CATEGORY_ADMIN_ACTION,
            'activity_description' => $description,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject?->id,
            'severity' => $severity,
            'method' => request()->method(),
            'url' => request()->fullUrl(),
            'route_name' => request()->route()?->getName(),
            'additional_data' => $additionalData
        ]);
    }

    /**
     * Log security events
     */
    public function logSecurityEvent($eventType, $description, $severity = ActivityLog::SEVERITY_HIGH, $user = null, $additionalData = [])
    {
        $user = $user ?? $this->getCurrentUser();
        
        return ActivityLog::logActivity([
            'actor_type' => $user ? get_class($user) : null,
            'actor_id' => $user?->id,
            'actor_email' => $user?->email,
            'event_type' => $eventType,
            'activity_category' => ActivityLog::CATEGORY_SECURITY,
            'activity_description' => $description,
            'severity' => $severity,
            'method' => request()->method(),
            'url' => request()->fullUrl(),
            'route_name' => request()->route()?->getName(),
            'additional_data' => $additionalData
        ]);
    }

    /**
     * Log system events
     */
    public function logSystemEvent($eventType, $description, $severity = ActivityLog::SEVERITY_LOW, $additionalData = [])
    {
        return ActivityLog::logActivity([
            'event_type' => $eventType,
            'activity_category' => ActivityLog::CATEGORY_SYSTEM,
            'activity_description' => $description,
            'severity' => $severity,
            'additional_data' => $additionalData
        ]);
    }

    /**
     * Log data export events
     */
    public function logDataExport($exportType, $user = null, $filters = [])
    {
        $user = $user ?? $this->getCurrentUser();
        
        return ActivityLog::logActivity([
            'actor_type' => $user ? get_class($user) : null,
            'actor_id' => $user?->id,
            'actor_email' => $user?->email,
            'event_type' => ActivityLog::EVENT_EXPORT,
            'activity_category' => ActivityLog::CATEGORY_DATA_EXPORT,
            'activity_description' => "Exported {$exportType} data",
            'severity' => ActivityLog::SEVERITY_MEDIUM,
            'method' => request()->method(),
            'url' => request()->fullUrl(),
            'route_name' => request()->route()?->getName(),
            'additional_data' => ['export_type' => $exportType, 'filters' => $filters]
        ]);
    }

    /**
     * Log user page access
     */
    public function logPageAccess($pageName, $user = null)
    {
        $user = $user ?? $this->getCurrentUser();
        
        return ActivityLog::logActivity([
            'actor_type' => $user ? get_class($user) : null,
            'actor_id' => $user?->id,
            'actor_email' => $user?->email,
            'event_type' => ActivityLog::EVENT_VIEW,
            'activity_category' => $this->getCategoryFromRoute(request()->route()?->getName()),
            'activity_description' => "Accessed {$pageName}",
            'severity' => ActivityLog::SEVERITY_LOW,
            'method' => request()->method(),
            'url' => request()->fullUrl(),
            'route_name' => request()->route()?->getName(),
        ]);
    }

    /**
     * Get current authenticated user from any guard
     */
    private function getCurrentUser()
    {
        if (Auth::guard('web')->check()) {
            return Auth::guard('web')->user();
        } elseif (Auth::guard('admin')->check()) {
            return Auth::guard('admin')->user();
        } elseif (Auth::guard('collector')->check()) {
            return Auth::guard('collector')->user();
        }
        
        return null;
    }

    /**
     * Determine severity based on event type and status
     */
    private function determineSeverity($eventType, $status)
    {
        if ($status === ActivityLog::STATUS_FAILED) {
            return match($eventType) {
                ActivityLog::EVENT_FAILED_LOGIN => ActivityLog::SEVERITY_MEDIUM,
                ActivityLog::EVENT_TWO_FACTOR_FAILED => ActivityLog::SEVERITY_HIGH,
                ActivityLog::EVENT_ACCOUNT_LOCKED => ActivityLog::SEVERITY_CRITICAL,
                default => ActivityLog::SEVERITY_MEDIUM
            };
        }

        return match($eventType) {
            ActivityLog::EVENT_LOGIN => ActivityLog::SEVERITY_LOW,
            ActivityLog::EVENT_LOGOUT => ActivityLog::SEVERITY_LOW,
            ActivityLog::EVENT_PASSWORD_RESET => ActivityLog::SEVERITY_MEDIUM,
            ActivityLog::EVENT_PASSWORD_CHANGE => ActivityLog::SEVERITY_MEDIUM,
            ActivityLog::EVENT_TWO_FACTOR_VERIFY => ActivityLog::SEVERITY_MEDIUM,
            default => ActivityLog::SEVERITY_LOW
        };
    }

    /**
     * Get authentication description
     */
    private function getAuthDescription($eventType, $status)
    {
        $statusText = $status === ActivityLog::STATUS_SUCCESS ? 'successfully' : 'failed to';
        
        return match($eventType) {
            ActivityLog::EVENT_LOGIN => "{$statusText} logged in",
            ActivityLog::EVENT_LOGOUT => "{$statusText} logged out",
            ActivityLog::EVENT_FAILED_LOGIN => "failed login attempt",
            ActivityLog::EVENT_PASSWORD_RESET => "{$statusText} reset password",
            ActivityLog::EVENT_PASSWORD_CHANGE => "{$statusText} changed password",
            ActivityLog::EVENT_TWO_FACTOR_VERIFY => "{$statusText} verified 2FA",
            ActivityLog::EVENT_TWO_FACTOR_FAILED => "failed 2FA verification",
            ActivityLog::EVENT_ACCOUNT_LOCKED => "account was locked",
            default => $eventType
        };
    }

    /**
     * Get model change description
     */
    private function getModelChangeDescription($eventType, $model)
    {
        $modelName = class_basename($model);
        
        return match($eventType) {
            ActivityLog::EVENT_CREATE => "created {$modelName}",
            ActivityLog::EVENT_UPDATE => "updated {$modelName}",
            ActivityLog::EVENT_DELETE => "deleted {$modelName}",
            ActivityLog::EVENT_VIEW => "viewed {$modelName}",
            ActivityLog::EVENT_ASSIGN => "assigned {$modelName}",
            ActivityLog::EVENT_UNASSIGN => "unassigned {$modelName}",
            ActivityLog::EVENT_STATUS_CHANGE => "changed {$modelName} status",
            default => "{$eventType} {$modelName}"
        };
    }

    /**
     * Get category from model type
     */
    private function getCategoryFromModel($model)
    {
        $modelClass = get_class($model);
        
        return match(class_basename($modelClass)) {
            'BinReport' => ActivityLog::CATEGORY_REPORT_MANAGEMENT,
            'User' => ActivityLog::CATEGORY_USER_MANAGEMENT,
            'Admin' => ActivityLog::CATEGORY_USER_MANAGEMENT,
            'Collector' => ActivityLog::CATEGORY_COLLECTOR_MANAGEMENT,
            'CollectionSchedule' => ActivityLog::CATEGORY_ADMIN_ACTION,
            default => ActivityLog::CATEGORY_SYSTEM
        };
    }

    /**
     * Get category from route name
     */
    private function getCategoryFromRoute($routeName)
    {
        if (str_contains($routeName, 'admin')) {
            return ActivityLog::CATEGORY_ADMIN_ACTION;
        } elseif (str_contains($routeName, 'collector')) {
            return ActivityLog::CATEGORY_COLLECTOR_MANAGEMENT;
        } elseif (str_contains($routeName, 'report')) {
            return ActivityLog::CATEGORY_REPORT_MANAGEMENT;
        } elseif (str_contains($routeName, 'user')) {
            return ActivityLog::CATEGORY_USER_MANAGEMENT;
        }
        
        return ActivityLog::CATEGORY_SYSTEM;
    }

    /**
     * Sanitize data to remove sensitive information
     */
    private function sanitizeData($data)
    {
        if (is_array($data)) {
            $sensitiveFields = ['password', 'password_confirmation', 'token', 'api_key', 'secret'];
            
            foreach ($sensitiveFields as $field) {
                if (isset($data[$field])) {
                    $data[$field] = '***REDACTED***';
                }
            }
        }
        
        return $data;
    }

    /**
     * Get recent suspicious activities
     */
    public function getRecentSuspiciousActivities($limit = 50)
    {
        return ActivityLog::securityEvents()
            ->where('severity', '>=', ActivityLog::SEVERITY_MEDIUM)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get activity statistics for dashboard
     */
    public function getActivityStats($days = 30)
    {
        $startDate = now()->subDays($days);
        
        return [
            'total_activities' => ActivityLog::where('created_at', '>=', $startDate)->count(),
            'failed_logins' => ActivityLog::where('event_type', ActivityLog::EVENT_FAILED_LOGIN)
                ->where('created_at', '>=', $startDate)->count(),
            'security_events' => ActivityLog::securityEvents()
                ->where('created_at', '>=', $startDate)->count(),
            'critical_events' => ActivityLog::where('severity', ActivityLog::SEVERITY_CRITICAL)
                ->where('created_at', '>=', $startDate)->count(),
        ];
    }
}