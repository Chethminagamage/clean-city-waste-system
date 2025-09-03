<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\WasteReport;
use App\Services\GamificationService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $gamificationService;

    public function __construct(GamificationService $gamificationService)
    {
        $this->gamificationService = $gamificationService;
    }

    public function index()
    {
        $userId = auth()->id();
        $user = auth()->user();

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

        // Initialize gamification for user (creates record if doesn't exist)
        $gamificationStats = $this->gamificationService->getUserStats($user);

        return view('resident.dashboard', compact('stats', 'reports', 'gamificationStats'));
    }
}