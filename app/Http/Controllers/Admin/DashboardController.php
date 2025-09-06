<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\WasteReport;
use App\Repositories\WasteReportRepository;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $wasteReportRepository;

    public function __construct(WasteReportRepository $wasteReportRepository)
    {
        $this->wasteReportRepository = $wasteReportRepository;
    }

    public function index()
    {
        // Get the authenticated admin
        $admin = auth('admin')->user();
        
        // Get unread notifications
        $notifications = $admin->unreadNotifications()->latest()->take(10)->get();
        $urgentNotifications = $admin->unreadNotifications()
            ->where('data->type', 'urgent_bin_report')
            ->get();
        
        // Get stats using repository
        $stats = $this->wasteReportRepository->getAdminDashboardStats();
        extract($stats); // Extract totalReports, completedReports, pendingReports, urgentReports
        
        // Get recent urgent reports using repository
        $recentUrgentReports = $this->wasteReportRepository->getRecentUrgentReports(5);
        
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
