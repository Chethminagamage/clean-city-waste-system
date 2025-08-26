<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\WasteReport;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get the authenticated admin
        $admin = auth('admin')->user();
        
        // Get unread notifications
        $notifications = $admin->unreadNotifications()->latest()->take(10)->get();
        $urgentNotifications = $admin->unreadNotifications()
            ->where('type', 'App\Notifications\UrgentBinReport')
            ->get();
        
        // Get stats
        $totalReports = WasteReport::count();
        $completedReports = WasteReport::whereIn('status', ['collected', 'closed'])->count();
        $pendingReports = WasteReport::where('status', 'pending')->count();
        $urgentReports = WasteReport::where('is_urgent', true)->count();
        
        // Get recent urgent reports
        $recentUrgentReports = WasteReport::where('is_urgent', true)
            ->with('resident')
            ->latest('urgent_reported_at')
            ->take(5)
            ->get();
        
        return view('admin.dashboard', compact(
            'notifications',
            'urgentNotifications', 
            'totalReports',
            'completedReports',
            'pendingReports',
            'urgentReports',
            'recentUrgentReports'
        ));
    }
}
