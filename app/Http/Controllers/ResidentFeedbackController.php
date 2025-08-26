<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Feedback;

class ResidentFeedbackController extends Controller
{
    /**
     * Display resident's feedback history
     */
    public function index()
    {
        $feedbacks = Feedback::with(['wasteReport'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('resident.feedback.index', compact('feedbacks'));
    }

    /**
     * Show specific feedback and admin response
     */
    public function show($id)
    {
        $feedback = Feedback::with(['wasteReport', 'adminRespondedBy'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        // Mark related notification as read if exists
        auth()->user()->notifications()
            ->where('data->feedback_id', $id)
            ->where('data->type', 'feedback_response')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('resident.feedback.show', compact('feedback'));
    }

    /**
     * Get feedback summary for dashboard
     */
    public function getSummary()
    {
        $userId = Auth::id();
        
        return [
            'total_feedback' => Feedback::where('user_id', $userId)->count(),
            'pending_responses' => Feedback::where('user_id', $userId)
                ->whereNull('admin_response')->count(),
            'recent_responses' => Feedback::where('user_id', $userId)
                ->whereNotNull('admin_response')
                ->where('admin_responded_at', '>=', now()->subDays(7))
                ->count(),
            'average_rating_given' => round(
                Feedback::where('user_id', $userId)->avg('rating') ?? 0, 1
            )
        ];
    }

    /**
     * Mark all feedback responses as read
     */
    public function markResponsesRead()
    {
        auth()->user()->notifications()
            ->where('data->type', 'feedback_response')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /**
     * Rate admin response (helpful/not helpful)
     */
    public function rateResponse(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|in:helpful,not_helpful'
        ]);

        $feedback = Feedback::where('user_id', Auth::id())->findOrFail($id);
        
        // Add a response rating field to track user satisfaction
        $feedback->update([
            'response_rating' => $request->rating,
            'response_rated_at' => now()
        ]);

        return response()->json(['success' => true]);
    }
}
