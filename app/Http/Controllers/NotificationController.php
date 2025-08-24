<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification; 

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // latest first (chronological, newest on top)
        $notifications = $user->notifications()->latest()->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    public function show(Request $request, DatabaseNotification $notification)
    {
        // Security: ensure the notification belongs to this user
        abort_unless($notification->notifiable_id === $request->user()->id, 403);

        // Mark as read and redirect to the stored URL (fallback to history page)
        $notification->markAsRead();
        $url = $notification->data['url'] ?? route('resident.reports.index');

        return redirect()->to($url);
    }

    public function open(DatabaseNotification $notification)
    {
        abort_unless($notification->notifiable_id === auth()->id(), 403);
        $notification->markAsRead();

        // Prefer report_id if we have it
        if ($id = data_get($notification->data, 'report_id')) {
            // deleted? -> toast + back to list/history
            if (! \App\Models\WasteReport::whereKey($id)->exists()) {
                return redirect()->route('resident.reports.index')
                    ->with('toast.error', 'That report no longer exists (it may have been deleted).');
            }
            return redirect()->route('resident.reports.show', $id);
        }

        // else fall back to stored URL (which might be relative for new ones)
        $url = data_get($notification->data, 'url') ?: route('resident.reports.index');
        return redirect()->to($url);
    }

    public function markRead(Request $request, string $id)
    {
        $n = $request->user()->notifications()->whereKey($id)->firstOrFail();
        $n->markAsRead();

        // open detail page when clicked
        $url = $n->data['url'] ?? route('resident.reports.index');
        return redirect($url);
    }

    public function markAllRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();
        return back()->with('ok', 'All notifications marked as read.');
    }
}