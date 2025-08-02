<?php 

namespace App\Http\Controllers\Collector;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\WasteReport;

class CollectorDashboardController extends Controller
{
    public function index()
    {
        $collector = Auth::user();

        // Fetch reports assigned to this collector
        $assignedReports = WasteReport::with('resident')
            ->where('collector_id', $collector->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('collector.dashboard', compact('collector', 'assignedReports'));
    }

    public function updateLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $user = Auth::user();
        $user->latitude = $request->latitude;
        $user->longitude = $request->longitude;
        $user->location = $request->location ?? null;
        $user->save();

        return response()->json(['success' => true]);
    }

    public function markAsCollected($id)
    {
        $report = WasteReport::findOrFail($id);

        if ($report->collector_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        $report->status = 'Collected';
        $report->save();

        return redirect()->back()->with('success', 'Report marked as collected.');
    }
}