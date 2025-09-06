<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Feedback;
use App\Models\WasteReport;
use App\Services\GamificationService;

class FeedbackController extends Controller
{
    protected $gamificationService;

    public function __construct(GamificationService $gamificationService)
    {
        $this->gamificationService = $gamificationService;
    }
    /**
     * Show the general feedback form
     */
    public function create()
    {
        return view('feedback.general');
    }

    /**
     * Store general feedback
     */
    public function store(Request $request)
    {
        $request->validate([
            'feedback_type' => 'required|string|max:255',
            'rating' => 'required|integer|between:1,5',
            'message' => 'nullable|string|max:1000',
        ]);

        Feedback::create([
            'user_id' => Auth::id(),
            'feedback_type' => $request->feedback_type,
            'rating' => $request->rating,
            'message' => $request->message,
            'status' => 'pending'
        ]);

        // Award points for providing feedback
        try {
            $user = Auth::user();
            $this->gamificationService->awardPoints(
                $user,
                'feedback_given',
                null,
                'Earned points for providing general feedback',
                [
                    'feedback_type' => $request->feedback_type,
                    'rating' => $request->rating
                ]
            );
        } catch (\Exception $e) {
            // Log error but don't fail the feedback submission
            \Log::error('Failed to award feedback points: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Feedback submitted successfully! We appreciate your input.');
    }
    
    /**
     * Show the form to submit feedback for a waste report
     */
    public function createForReport($reportId)
    {
        $report = WasteReport::findOrFail($reportId);
        
        // Check if user is the report owner
        if ($report->resident_id !== Auth::id()) {
            return redirect()->back()->with('error', 'You can only provide feedback for your own reports');
        }
        
        // Check if report is collected or closed (allow feedback for both)
        if (!in_array(strtolower($report->status), ['collected', 'closed'])) {
            return redirect()->back()->with('error', 'You can only provide feedback for collected or closed reports');
        }
        
        // Check if feedback already exists
        if ($report->hasFeedback()) {
            return redirect()->back()->with('error', 'You have already submitted feedback for this report');
        }
        
        return view('feedback.report', compact('report'));
    }

    /**
     * Store feedback for a waste report
     */
    public function storeForReport(Request $request, $reportId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'message' => 'nullable|string|max:500',
        ]);

        $report = WasteReport::findOrFail($reportId);
        
        // Check if user is the report owner
        if ($report->resident_id !== Auth::id()) {
            return redirect()->back()->with('error', 'You can only provide feedback for your own reports');
        }
        
        // Check if report is collected or closed (allow feedback for both)
        if (!in_array(strtolower($report->status), ['collected', 'closed'])) {
            return redirect()->back()->with('error', 'You can only provide feedback for collected or closed reports');
        }
        
        // Check if feedback already exists
        if ($report->hasFeedback()) {
            return redirect()->back()->with('error', 'You have already submitted feedback for this report');
        }
        
        // Create feedback
        Feedback::create([
            'user_id' => Auth::id(),
            'waste_report_id' => $report->id,
            'rating' => $request->rating,
            'message' => $request->message,
            'feedback_type' => 'report',
        ]);

        // Award points for providing report feedback
        try {
            $user = Auth::user();
            $this->gamificationService->awardPoints(
                $user,
                'feedback_given',
                null,
                'Earned points for providing report feedback',
                [
                    'report_id' => $report->id,
                    'rating' => $request->rating,
                    'feedback_type' => 'report'
                ]
            );
        } catch (\Exception $e) {
            // Log error but don't fail the feedback submission
            \Log::error('Failed to award feedback points: ' . $e->getMessage());
        }
        
        return redirect()->route('resident.reports.index')->with('success', 'Thank you for your feedback!');
    }
}