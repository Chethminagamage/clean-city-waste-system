<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AuditTrailController extends Controller
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /**
     * Display audit trail dashboard
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with(['actor', 'subject'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('actor_type')) {
            $query->where('actor_type', $request->actor_type);
        }

        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        if ($request->filled('category')) {
            $query->where('activity_category', $request->category);
        }

        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('activity_description', 'like', "%{$search}%")
                  ->orWhere('actor_email', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        $activities = $query->paginate(15);

        // Get statistics for dashboard
        $stats = $this->getActivityStats();

        return view('admin.audit-trail.index', compact('activities', 'stats'));
    }

    /**
     * Get activity statistics
     */
    private function getActivityStats()
    {
        $today = Carbon::today();
        $weekAgo = Carbon::now()->subWeek();
        $monthAgo = Carbon::now()->subMonth();

        return [
            'today' => ActivityLog::whereDate('created_at', $today)->count(),
            'this_week' => ActivityLog::where('created_at', '>=', $weekAgo)->count(),
            'this_month' => ActivityLog::where('created_at', '>=', $monthAgo)->count(),
            'failed_logins_today' => ActivityLog::where('event_type', 'failed_login')
                ->whereDate('created_at', $today)->count(),
            // More specific security events - only actual security alerts, not all auth
            'security_events_week' => ActivityLog::where('created_at', '>=', $weekAgo)
                ->where(function($query) {
                    $query->where('activity_category', 'security')
                          ->orWhere('severity', 'high')
                          ->orWhere('severity', 'critical')
                          ->orWhereIn('event_type', ['failed_login', 'account_locked', '2fa_failed']);
                })->count(),
            'critical_events_month' => ActivityLog::where('severity', 'critical')
                ->where('created_at', '>=', $monthAgo)->count(),
            // Add authentication events separately for clarity
            'auth_events_week' => ActivityLog::where('activity_category', 'authentication')
                ->where('created_at', '>=', $weekAgo)->count(),
        ];
    }

    /**
     * Display detailed activity log
     */
    public function show($id)
    {
        $activity = ActivityLog::with(['actor', 'subject'])->findOrFail($id);
        
        // Log that admin viewed this activity
        $this->activityLogService->logAdminAction(
            "Viewed activity log #{$id}",
            'view',
            $activity,
            'low'
        );

        return view('admin.audit-trail.show', compact('activity'));
    }

    /**
     * Export activity logs to CSV
     */
    public function export(Request $request)
    {
        $query = ActivityLog::with(['actor', 'subject'])
            ->orderBy('created_at', 'desc');

        // Apply same filters as index
        if ($request->filled('actor_type')) {
            $query->where('actor_type', $request->actor_type);
        }

        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        if ($request->filled('category')) {
            $query->where('activity_category', $request->category);
        }

        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $activities = $query->limit(10000)->get(); // Limit export size

        // Log the export
        $this->activityLogService->logDataExport('Activity Logs', null, $request->only([
            'actor_type', 'event_type', 'category', 'severity', 'date_from', 'date_to'
        ]));

        $filename = 'activity_logs_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        return response()->stream(function () use ($activities) {
            $handle = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($handle, [
                'ID',
                'Date/Time',
                'Actor Type',
                'Actor Email',
                'Event Type',
                'Category',
                'Description',
                'Subject Type',
                'Subject ID',
                'IP Address',
                'User Agent',
                'Status',
                'Severity',
                'Notes'
            ]);

            // CSV data
            foreach ($activities as $activity) {
                fputcsv($handle, [
                    $activity->id,
                    $activity->created_at->format('Y-m-d H:i:s'),
                    $activity->actor_type,
                    $activity->actor_email,
                    $activity->event_type,
                    $activity->activity_category,
                    $activity->activity_description,
                    $activity->subject_type,
                    $activity->subject_id,
                    $activity->ip_address,
                    substr($activity->user_agent, 0, 100), // Truncate user agent
                    $activity->status,
                    $activity->severity,
                    $activity->notes
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Get security events for dashboard widget
     */
    public function securityEvents()
    {
        $events = ActivityLog::securityEvents()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json($events);
    }

    /**
     * Get recent suspicious activities
     */
    public function suspiciousActivities()
    {
        $activities = $this->activityLogService->getRecentSuspiciousActivities(20);
        
        return response()->json($activities);
    }
}
