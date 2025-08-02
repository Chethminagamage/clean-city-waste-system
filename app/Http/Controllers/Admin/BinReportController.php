<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\WasteReport;
use Illuminate\Support\Facades\DB;

class BinReportController extends Controller
{
    public function index()
    {
        $reports = WasteReport::with('resident')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

        return view('admin.bin_reports', compact('reports'));
    }

    public function assignNearestCollector($reportId)
    {
        $report = WasteReport::findOrFail($reportId);

        // Ensure report has lat/lng
        if (!$report->latitude || !$report->longitude) {
            return back()->with('error', 'Report does not have location data.');
        }

        // Get all active collectors
        $collectors = User::where('role', 'collector')
            ->where('status', 1)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        if ($collectors->isEmpty()) {
            return back()->with('error', 'No active collectors with location found.');
        }

        // Calculate distances using Haversine formula
        $nearest = $collectors->map(function ($collector) use ($report) {
            $theta = $report->longitude - $collector->longitude;
            $dist = sin(deg2rad($report->latitude)) * sin(deg2rad($collector->latitude))
                + cos(deg2rad($report->latitude)) * cos(deg2rad($collector->latitude)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            return [
                'collector' => $collector,
                'distance' => $miles,
            ];
        })->sortBy('distance')->first();

        $report->collector_id = $nearest['collector']->id;
        $report->status = 'Assigned';
        $report->save();

        return back()->with('success', 'Collector assigned successfully!');
    }

    public function getNearbyCollectors($id)
{
    $report = WasteReport::findOrFail($id);

    $reportLat = $report->latitude;
    $reportLng = $report->longitude;

    // Fetch nearest collectors using Haversine
    $collectors = DB::table('users')
        ->select('id', 'name', 'latitude', 'longitude',
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

// Manual assign from dropdown
public function assignCollector(Request $request, WasteReport $report)
{
    $request->validate([
        'collector_id' => 'required|exists:users,id',
    ]);

    $report->collector_id = $request->collector_id;
    $report->status = 'Assigned';
    $report->save();

    return redirect()->back()->with('success', 'Collector assigned successfully.');
}
}
