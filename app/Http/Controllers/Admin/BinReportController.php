<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\WasteReport;
use Illuminate\Support\Facades\DB;
use App\Notifications\ReportStatusUpdated;

class BinReportController extends Controller
{
    /**
     * Show list of all waste reports
     */
    public function index()
    {
        $reports = WasteReport::with('resident')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.bin_reports', compact('reports'));
    }

    /**
     * Automatically assign nearest active collector using Haversine
     */
    public function assignNearestCollector($reportId)
    {
        $report = WasteReport::findOrFail($reportId);

        if (!$report->latitude || !$report->longitude) {
            return back()->with('error', 'Report does not have location data.');
        }

        // Get all active collectors with valid coordinates
        $collectors = User::query()
            ->where('role', 'collector')
            ->where('status', 1)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        if ($collectors->isEmpty()) {
            return back()->with('error', 'No active collectors with location found.');
        }

        // Find the nearest using the Haversine formula (KM)
        $reportLat = (float) $report->latitude;
        $reportLng = (float) $report->longitude;

        $nearest = $collectors->map(function ($collector) use ($reportLat, $reportLng) {
            $colLat = (float) $collector->latitude;
            $colLng = (float) $collector->longitude;

            $theta = $reportLng - $colLng;
            $dist = sin(deg2rad($reportLat)) * sin(deg2rad($colLat))
                + cos(deg2rad($reportLat)) * cos(deg2rad($colLat)) * cos(deg2rad($theta));
            // Guard acos domain
            $dist = max(-1, min(1, $dist));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $distanceKm = $dist * 60 * 1.1515 * 1.609344; // Convert miles -> KM

            return [
                'collector' => $collector,
                'distance'  => $distanceKm,
            ];
        })->sortBy('distance')->first();

        // Assign collector with a consistent lowercase status
        $report->collector_id = $nearest['collector']->id;
        $report->status = WasteReport::ST_ASSIGNED; // 'assigned'
        $report->save();

        // Notify resident (optional)
        if ($report->resident) {
            $report->resident->notify(new ReportStatusUpdated($report, WasteReport::ST_ASSIGNED));
        }

        return back()->with('success', 'Nearest collector assigned successfully!');
    }

    /**
     * Get nearest 10 collectors to a report as JSON
     */
    public function getNearbyCollectors($id)
    {
        $report = WasteReport::findOrFail($id);

        if (!$report->latitude || !$report->longitude) {
            return response()->json(['error' => 'Location data missing'], 400);
        }

        $reportLat = (float) $report->latitude;
        $reportLng = (float) $report->longitude;

        $collectors = DB::table('users')
            ->select(
                'id', 'name', 'latitude', 'longitude',
                DB::raw("(6371 * acos(
                    cos(radians($reportLat)) *
                    cos(radians(latitude)) *
                    cos(radians(longitude) - radians($reportLng)) +
                    sin(radians($reportLat)) *
                    sin(radians(latitude))
                )) AS distance")
            )
            ->where('role', 'collector')
            ->where('status', 1)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->orderBy('distance')
            ->limit(10)
            ->get();

        return response()->json($collectors);
    }

    /**
     * Assign collector manually from dropdown
     */
    public function assignCollector(Request $request, WasteReport $report)
    {
        $validated = $request->validate([
            'collector_id' => ['required', 'exists:users,id'],
        ]);

        $report->collector_id = $validated['collector_id'];
        $report->status = WasteReport::ST_ASSIGNED; // 'assigned'
        $report->save();

        // Notify resident (optional)
        if ($report->resident) {
            $report->resident->notify(new ReportStatusUpdated($report, WasteReport::ST_ASSIGNED));
        }

        return redirect()->back()->with('success', 'Collector assigned successfully.');
    }
    
    /**
     * Close a collected report
     */
    public function closeReport($id)
    {
        $report = WasteReport::findOrFail($id);
        
        if ($report->status !== WasteReport::ST_COLLECTED) {
            return redirect()->back()->with('error', 'Only collected reports can be closed.');
        }
        
        $report->status = WasteReport::ST_CLOSED;
        $report->save();
        
        // Notify resident (optional)
        if ($report->resident) {
            $report->resident->notify(new ReportStatusUpdated($report, WasteReport::ST_CLOSED));
        }
        
        return redirect()->back()->with('success', 'Report has been closed successfully.');
    }
}