<?php

namespace App\Repositories;

use App\Models\WasteReport;
use Illuminate\Database\Eloquent\Collection;

/**
 * Repository class for WasteReport queries
 * This centralizes common query logic without changing functionality
 */
class WasteReportRepository
{
    /**
     * Get active reports for collector (existing functionality preserved)
     */
    public function getActiveReportsForCollector(int $collectorId): Collection
    {
        return WasteReport::with('resident')
            ->activeForCollector($collectorId)
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Get completed reports for collector (existing functionality preserved)
     */
    public function getCompletedReportsForCollector(int $collectorId): Collection
    {
        return WasteReport::with('resident')
            ->completedForCollector($collectorId)
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Get all assigned reports for collector (existing functionality preserved)
     */
    public function getAllAssignedReportsForCollector(int $collectorId): Collection
    {
        return WasteReport::with('resident')
            ->forCollector($collectorId)
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Get reports for resident dashboard (existing functionality preserved)
     */
    public function getReportsForResident(int $residentId, int $limit = 10): Collection
    {
        return WasteReport::where('resident_id', $residentId)
            ->latest('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Get resident report statistics (existing functionality preserved)
     */
    public function getResidentReportStats(int $residentId): array
    {
        return [
            'total' => WasteReport::where('resident_id', $residentId)->count(),
            'pending' => WasteReport::where('resident_id', $residentId)->where('status', 'pending')->count(),
            'scheduled' => WasteReport::where('resident_id', $residentId)->whereIn('status', ['assigned','enroute'])->count(),
            'collected' => WasteReport::where('resident_id', $residentId)->where('status', 'collected')->count(),
        ];
    }

    /**
     * Get admin dashboard stats (existing functionality preserved)
     */
    public function getAdminDashboardStats(): array
    {
        return [
            'totalReports' => WasteReport::count(),
            'completedReports' => WasteReport::whereIn('status', ['collected', 'closed'])->count(),
            'pendingReports' => WasteReport::where('status', 'pending')->count(),
            'urgentReports' => WasteReport::where('is_urgent', true)->count(),
        ];
    }

    /**
     * Get recent urgent reports (existing functionality preserved)
     */
    public function getRecentUrgentReports(int $limit = 5): Collection
    {
        return WasteReport::where('is_urgent', true)
            ->with('resident')
            ->latest('urgent_reported_at')
            ->limit($limit)
            ->get();
    }
}