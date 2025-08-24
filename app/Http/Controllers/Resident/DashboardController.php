<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\WasteReport;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // recent reports for the table/list on the dashboard
        $reports = WasteReport::where('resident_id', $userId)
            ->latest('created_at')
            ->limit(10)       // adjust if you want more/less
            ->get();

        // stats used by the cards
        $stats = [
            'total'     => WasteReport::where('resident_id', $userId)->count(),
            'pending'   => WasteReport::where('resident_id', $userId)->where('status', 'pending')->count(),
            'scheduled' => WasteReport::where('resident_id', $userId)->whereIn('status', ['assigned','enroute'])->count(),
            'collected' => WasteReport::where('resident_id', $userId)->where('status', 'collected')->count(),
            // add 'closed' or other buckets if you later need them
        ];

        return view('resident.dashboard', compact('stats', 'reports'));
    }
}