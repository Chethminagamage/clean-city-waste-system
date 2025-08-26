<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feedback;
use App\Models\WasteReport;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FeedbackController extends Controller
{
    /**
     * Display feedback dashboard with analytics
     */
    public function index(Request $request)
    {
        $timeRange = $request->get('range', '30'); // days
        $feedbackType = $request->get('type', 'all');
        $rating = $request->get('rating', 'all');

        // Base query
        $query = Feedback::with(['user', 'wasteReport'])
            ->where('created_at', '>=', now()->subDays($timeRange));

        // Apply filters
        if ($feedbackType !== 'all') {
            $query->where('feedback_type', $feedbackType);
        }

        if ($rating !== 'all') {
            $query->where('rating', $rating);
        }

        // Get feedbacks with pagination
        $feedbacks = $query->latest()->paginate(20);

        // Calculate analytics (with error handling)
        try {
            $analytics = $this->getFeedbackAnalytics($timeRange, $feedbackType);
        } catch (\Exception $e) {
            // Fallback analytics if there's an error
            $analytics = [
                'total_feedback' => 0,
                'average_rating' => 0,
                'rating_distribution' => [],
                'feedback_by_type' => [],
                'low_ratings' => 0,
                'high_ratings' => 0,
                'pending_responses' => 0,
                'response_rate' => 0,
                'recent_trends' => [],
                'common_issues' => []
            ];
        }

        return view('admin.feedback.index', compact('feedbacks', 'analytics', 'timeRange', 'feedbackType', 'rating'));
    }

    /**
     * Show detailed feedback with report context
     */
    public function show($id)
    {
        $feedback = Feedback::with(['user', 'wasteReport.collector'])
            ->findOrFail($id);

        return view('admin.feedback.show', compact('feedback'));
    }

    /**
     * Respond to feedback
     */
    public function respond(Request $request, $id)
    {
        $request->validate([
            'admin_response' => 'required|string|max:1000'
        ]);

        $feedback = Feedback::findOrFail($id);
        
        $feedback->update([
            'admin_response' => $request->admin_response,
            'admin_responded_by' => auth()->guard('admin')->id(),
            'admin_responded_at' => now()
        ]);

        // Send notification to user about the response
        if ($feedback->user) {
            $feedback->user->notify(new \App\Notifications\FeedbackResponseReceived($feedback));
        }

        return redirect()->back()->with('success', 'Response sent successfully! User has been notified.');
    }

    /**
     * Mark feedback as resolved/action taken
     */
    public function markResolved($id)
    {
        $feedback = Feedback::findOrFail($id);
        
        $feedback->update([
            'status' => 'resolved',
            'resolved_by' => auth()->guard('admin')->id(),
            'resolved_at' => now()
        ]);

        return redirect()->back()->with('success', 'Feedback marked as resolved!');
    }

    /**
     * Get comprehensive analytics
     */
    private function getFeedbackAnalytics($days, $type = 'all')
    {
        $baseQuery = Feedback::where('created_at', '>=', now()->subDays($days));
        
        if ($type !== 'all') {
            $baseQuery->where('feedback_type', $type);
        }

        $totalFeedback = $baseQuery->count();
        $averageRating = $totalFeedback > 0 ? round($baseQuery->avg('rating') ?? 0, 1) : 0;

        return [
            'total_feedback' => $totalFeedback,
            'average_rating' => $averageRating,
            'rating_distribution' => $baseQuery->select('rating', DB::raw('count(*) as count'))
                ->groupBy('rating')
                ->pluck('count', 'rating')
                ->toArray(),
            'feedback_by_type' => Feedback::where('created_at', '>=', now()->subDays($days))
                ->select('feedback_type', DB::raw('count(*) as count'))
                ->groupBy('feedback_type')
                ->pluck('count', 'feedback_type')
                ->toArray(),
            'low_ratings' => $baseQuery->where('rating', '<=', 2)->count(),
            'high_ratings' => $baseQuery->where('rating', '>=', 4)->count(),
            'pending_responses' => $baseQuery->whereNull('admin_response')->count(),
            'response_rate' => $this->calculateResponseRate($baseQuery),
            'recent_trends' => $this->getRecentTrends($days, $type),
            'common_issues' => $this->getCommonIssues($days, $type)
        ];
    }

    /**
     * Calculate admin response rate
     */
    private function calculateResponseRate($query)
    {
        $total = $query->count();
        if ($total === 0) return 0;
        
        $responded = $query->whereNotNull('admin_response')->count();
        return round(($responded / $total) * 100, 1);
    }

    /**
     * Get rating trends over time
     */
    private function getRecentTrends($days, $type)
    {
        $query = Feedback::where('created_at', '>=', now()->subDays($days));
        
        if ($type !== 'all') {
            $query->where('feedback_type', $type);
        }

        return $query->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('AVG(rating) as avg_rating'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'desc')
            ->limit(7)
            ->get();
    }

    /**
     * Identify common issues from feedback messages
     */
    private function getCommonIssues($days, $type)
    {
        $query = Feedback::where('created_at', '>=', now()->subDays($days))
            ->where('rating', '<=', 3) // Focus on problematic feedback
            ->whereNotNull('message');
            
        if ($type !== 'all') {
            $query->where('feedback_type', $type);
        }

        $messages = $query->pluck('message');
        
        // Simple keyword analysis (you could enhance this with NLP)
        $keywords = ['late', 'delay', 'missed', 'dirty', 'damage', 'rude', 'slow', 'problem', 'issue', 'bad', 'poor'];
        $issues = [];
        
        foreach ($keywords as $keyword) {
            $count = $messages->filter(function ($message) use ($keyword) {
                return stripos($message, $keyword) !== false;
            })->count();
            
            if ($count > 0) {
                $issues[$keyword] = $count;
            }
        }
        
        arsort($issues);
        return array_slice($issues, 0, 10); // Top 10 issues
    }

    /**
     * Export feedback data
     */
    public function export(Request $request)
    {
        $timeRange = $request->get('range', '30');
        $format = $request->get('format', 'csv');
        
        $feedbacks = Feedback::with(['user', 'wasteReport'])
            ->where('created_at', '>=', now()->subDays($timeRange))
            ->get();

        if ($format === 'csv') {
            return $this->exportToCsv($feedbacks);
        }
        
        // Add other export formats as needed
    }

    /**
     * Export to CSV
     */
    private function exportToCsv($feedbacks)
    {
        $filename = 'feedback_export_' . now()->format('Y_m_d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response()->stream(function () use ($feedbacks) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'ID', 'User Name', 'User Email', 'Report ID', 'Feedback Type', 
                'Rating', 'Message', 'Admin Response', 'Status', 'Created At'
            ]);
            
            foreach ($feedbacks as $feedback) {
                fputcsv($file, [
                    $feedback->id,
                    $feedback->user->name ?? 'N/A',
                    $feedback->user->email ?? 'N/A',
                    $feedback->waste_report_id ?? 'N/A',
                    $feedback->feedback_type,
                    $feedback->rating,
                    $feedback->message,
                    $feedback->admin_response,
                    $feedback->status ?? 'pending',
                    $feedback->created_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        }, 200, $headers);
    }
}
