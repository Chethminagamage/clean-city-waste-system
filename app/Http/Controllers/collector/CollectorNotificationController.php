<?php

namespace App\Http\Controllers\Collector;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class CollectorNotificationController extends Controller
{
    /**
     * Display a listing of notifications for the collector.
     */
    public function index(Request $request)
    {
        $collector = $request->user();

        // Get all notifications for the collector, latest first
        $notifications = $collector->notifications()
            ->latest()
            ->paginate(15);

        return view('collector.notifications.index', compact('notifications'));
    }

    /**
     * Get unread notification count for AJAX requests.
     */
    public function getUnreadCount(Request $request)
    {
        $collector = $request->user();
        $count = $collector->unreadNotifications()->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Get recent notifications for dropdown.
     */
    public function getRecent(Request $request)
    {
        $collector = $request->user();
        $notifications = $collector->notifications()
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'data' => $notification->data,
                    'created_at' => $notification->created_at->toJSON(),
                ];
            });
        return response()->json($notifications);
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead(Request $request, $notificationId)
    {
        $collector = $request->user();
        
        $notification = $collector->notifications()
            ->where('id', $notificationId)
            ->first();

        if ($notification) {
            $notification->markAsRead();
            
            // Return the URL to redirect to
            $url = $notification->data['url'] ?? route('collector.dashboard');
            
            return response()->json([
                'success' => true,
                'url' => $url
            ]);
        }

        return response()->json(['success' => false], 404);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(Request $request)
    {
        $collector = $request->user();
        
        $collector->unreadNotifications()->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /**
     * Show a specific notification and mark it as read.
     */
    public function show(Request $request, DatabaseNotification $notification)
    {
    // Security: ensure the notification belongs to this collector
    abort_unless($notification->notifiable_id === $request->user()->id, 403);
    // Mark as read
    $notification->markAsRead();
    // Redirect to the stored URL
    $url = $notification->data['url'] ?? route('collector.dashboard');
    return redirect()->to($url);
    }

    /**
     * Delete a notification.
     */
    public function destroy(Request $request, $notificationId)
    {
        $collector = $request->user();
        
        $notification = $collector->notifications()
            ->where('id', $notificationId)
            ->first();

        if ($notification) {
            $notification->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }
}
