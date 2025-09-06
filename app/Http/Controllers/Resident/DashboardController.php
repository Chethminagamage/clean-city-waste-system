<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\WasteReport;
use App\Services\GamificationService;
use App\Repositories\WasteReportRepository;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $gamificationService;
    protected $wasteReportRepository;

    public function __construct(
        GamificationService $gamificationService,
        WasteReportRepository $wasteReportRepository
    ) {
        $this->gamificationService = $gamificationService;
        $this->wasteReportRepository = $wasteReportRepository;
    }

    public function index()
    {
        $userId = auth()->id();
        $user = auth()->user();

        // recent reports for the table/list on the dashboard
        $reports = $this->wasteReportRepository->getReportsForResident($userId, 10);

        // stats used by the cards
        $stats = $this->wasteReportRepository->getResidentReportStats($userId);

        // Initialize gamification for user (creates record if doesn't exist)
        $gamificationStats = $this->gamificationService->getUserStats($user);

        return view('resident.dashboard', compact('stats', 'reports', 'gamificationStats'));
    }
}