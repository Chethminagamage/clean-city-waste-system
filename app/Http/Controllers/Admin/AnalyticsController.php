<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\WasteReport;
use App\Models\Feedback;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $timeRange = $request->get('range', 30); // Default 30 days
        $collectorId = $request->get('collector', null);

        // Get collector efficiency metrics
        $collectorMetrics = $this->getCollectorEfficiencyMetrics($timeRange, $collectorId);
        
        // Get overview statistics
        $overviewStats = $this->getOverviewStatistics($timeRange);
        
        // Get performance trends
        $performanceTrends = $this->getPerformanceTrends($timeRange);
        
        // Get all collectors for dropdown
        $collectors = User::where('role', 'collector')
            ->select('id', 'name', 'location')
            ->orderBy('name')
            ->get();

        return view('admin.analytics', compact(
            'collectorMetrics',
            'overviewStats', 
            'performanceTrends',
            'collectors',
            'timeRange',
            'collectorId'
        ));
    }

    private function getCollectorEfficiencyMetrics($timeRange, $collectorId = null)
    {
        $dateFrom = Carbon::now()->subDays($timeRange);
        
        $query = User::where('role', 'collector')
            ->withCount([
                'wasteReports as total_assigned' => function($q) use ($dateFrom) {
                    $q->where('created_at', '>=', $dateFrom);
                },
                'wasteReports as completed_reports' => function($q) use ($dateFrom) {
                    $q->where('created_at', '>=', $dateFrom)
                      ->where('status', 'collected');
                },
                'wasteReports as pending_reports' => function($q) use ($dateFrom) {
                    $q->where('created_at', '>=', $dateFrom)
                      ->whereIn('status', ['assigned', 'enroute']);
                }
            ]);

        if ($collectorId) {
            $query->where('id', $collectorId);
        }

        $collectors = $query->get()->map(function($collector) use ($dateFrom) {
            // Calculate completion rate
            $completionRate = $collector->total_assigned > 0 
                ? round(($collector->completed_reports / $collector->total_assigned) * 100, 1)
                : 0;

            // Calculate average completion time
            $avgCompletionTime = $this->getAverageCompletionTime($collector->id, $dateFrom);
            
            // Get feedback rating
            $avgRating = $this->getCollectorAverageRating($collector->id, $dateFrom);
            
            // Calculate efficiency score
            $efficiencyScore = $this->calculateEfficiencyScore(
                $completionRate,
                $avgCompletionTime,
                $avgRating,
                $collector->total_assigned
            );

            // Get recent activity
            $recentActivity = WasteReport::where('collector_id', $collector->id)
                ->where('updated_at', '>=', Carbon::now()->subHours(24))
                ->count();

            return [
                'id' => $collector->id,
                'name' => $collector->name,
                'location' => $collector->location,
                'total_assigned' => $collector->total_assigned,
                'completed_reports' => $collector->completed_reports,
                'pending_reports' => $collector->pending_reports,
                'completion_rate' => $completionRate,
                'avg_completion_time' => $avgCompletionTime,
                'avg_rating' => $avgRating,
                'efficiency_score' => $efficiencyScore,
                'recent_activity' => $recentActivity,
                'status' => $this->getCollectorStatus($collector->id)
            ];
        })->sortByDesc('efficiency_score');

        return $collectors;
    }

    private function getAverageCompletionTime($collectorId, $dateFrom)
    {
        $completedReports = WasteReport::where('collector_id', $collectorId)
            ->where('status', 'collected')
            ->where('created_at', '>=', $dateFrom)
            ->get();

        if ($completedReports->isEmpty()) {
            return null;
        }

        $totalHours = 0;
        $count = 0;

        foreach ($completedReports as $report) {
            if ($report->updated_at && $report->created_at) {
                $hours = $report->created_at->diffInHours($report->updated_at);
                $totalHours += $hours;
                $count++;
            }
        }

        return $count > 0 ? round($totalHours / $count, 1) : null;
    }

    private function getCollectorAverageRating($collectorId, $dateFrom)
    {
        $avgRating = Feedback::whereHas('wasteReport', function($q) use ($collectorId, $dateFrom) {
                $q->where('collector_id', $collectorId)
                  ->where('created_at', '>=', $dateFrom);
            })
            ->where('rating', '>', 0)
            ->avg('rating');

        return $avgRating ? round($avgRating, 1) : null;
    }

    private function calculateEfficiencyScore($completionRate, $avgCompletionTime, $avgRating, $totalAssigned)
    {
        $score = 0;

        // Completion rate (40% weight)
        $score += ($completionRate * 0.4);

        // Time efficiency (30% weight) - lower time is better
        if ($avgCompletionTime && $avgCompletionTime > 0) {
            $timeScore = max(0, 100 - ($avgCompletionTime * 2)); // Penalty for longer times
            $score += ($timeScore * 0.3);
        } else {
            $score += (50 * 0.3); // Neutral score if no data
        }

        // Rating (20% weight)
        if ($avgRating) {
            $ratingScore = ($avgRating / 5) * 100;
            $score += ($ratingScore * 0.2);
        } else {
            $score += (75 * 0.2); // Neutral score if no ratings
        }

        // Volume bonus (10% weight) - more assignments handled
        $volumeScore = min(100, $totalAssigned * 2); // Cap at 100
        $score += ($volumeScore * 0.1);

        return round(min(100, max(0, $score)), 1);
    }

    private function getCollectorStatus($collectorId)
    {
        $activeReports = WasteReport::where('collector_id', $collectorId)
            ->whereIn('status', ['assigned', 'enroute'])
            ->count();

        $lastActivity = WasteReport::where('collector_id', $collectorId)
            ->latest('updated_at')
            ->first();

        if ($activeReports > 0) {
            return 'active';
        } elseif ($lastActivity && $lastActivity->updated_at->diffInHours() <= 24) {
            return 'recently_active';
        } else {
            return 'idle';
        }
    }

    private function getOverviewStatistics($timeRange)
    {
        $dateFrom = Carbon::now()->subDays($timeRange);

        return [
            // Required for tests
            'total_reports' => WasteReport::where('created_at', '>=', $dateFrom)->count(),
            'completed_reports' => WasteReport::where('status', 'collected')
                ->where('updated_at', '>=', $dateFrom)->count(),
            'pending_reports' => WasteReport::whereIn('status', ['pending', 'assigned'])
                ->where('created_at', '>=', $dateFrom)->count(),
            
            // Additional metrics for dashboard
            'total_collectors' => User::where('role', 'collector')->count(),
            'active_collectors' => User::where('role', 'collector')
                ->whereHas('wasteReports', function($q) use ($dateFrom) {
                    $q->where('updated_at', '>=', Carbon::now()->subDays(7));
                })->count(),
            'total_collections' => WasteReport::where('status', 'collected')
                ->where('updated_at', '>=', $dateFrom)->count(),
            'avg_system_completion_rate' => $this->getSystemCompletionRate($dateFrom),
            'urgent_reports_handled' => WasteReport::where('is_urgent', true)
                ->where('status', 'collected')
                ->where('updated_at', '>=', $dateFrom)->count(),
            'customer_satisfaction' => $this->getOverallCustomerSatisfaction($dateFrom)
        ];
    }

    private function getSystemCompletionRate($dateFrom)
    {
        $totalReports = WasteReport::where('created_at', '>=', $dateFrom)->count();
        $completedReports = WasteReport::where('status', 'collected')
            ->where('updated_at', '>=', $dateFrom)->count();

        return $totalReports > 0 ? round(($completedReports / $totalReports) * 100, 1) : 0;
    }

    private function getOverallCustomerSatisfaction($dateFrom)
    {
        $avgRating = Feedback::whereHas('wasteReport', function($q) use ($dateFrom) {
                $q->where('created_at', '>=', $dateFrom);
            })
            ->where('rating', '>', 0)
            ->avg('rating');

        return $avgRating ? round($avgRating, 1) : null;
    }

    private function getPerformanceTrends($timeRange)
    {
        $days = min($timeRange, 30); // Limit to 30 days for chart performance
        $trends = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dayStart = $date->copy()->startOfDay();
            $dayEnd = $date->copy()->endOfDay();

            $completed = WasteReport::where('status', 'collected')
                ->whereBetween('updated_at', [$dayStart, $dayEnd])
                ->count();

            $assigned = WasteReport::whereBetween('created_at', [$dayStart, $dayEnd])
                ->count();

            $trends[] = [
                'date' => $date->format('M j'),
                'completed' => $completed,
                'assigned' => $assigned,
                'completion_rate' => $assigned > 0 ? round(($completed / $assigned) * 100, 1) : 0
            ];
        }

        return $trends;
    }
}
