<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;

class AlertController extends Controller
{
    public function index()
    {
        // Get the first admin user (or implement proper admin authentication)
        $admin = Admin::first();
        
        if (!$admin) {
            // If no admin exists, show empty notifications
            $notifications = collect([]);
        } else {
            // Get all notifications for the admin, latest first
            $notifications = $admin->notifications()
                ->latest()
                ->paginate(10);
        }

        return view('admin.alerts', compact('notifications'));
    }

    public function markAsRead(Request $request, $notificationId)
    {
        $admin = Admin::first();
        
        if ($admin) {
            $notification = $admin->notifications()->where('id', $notificationId)->first();
            if ($notification) {
                $notification->markAsRead();
                return redirect()->back()->with('success', 'Notification marked as read');
            }
        }

        return redirect()->back()->with('error', 'Notification not found');
    }

    public function markAllAsRead(Request $request)
    {
        $admin = Admin::first();
        
        if ($admin) {
            $admin->unreadNotifications()->update(['read_at' => now()]);
            return redirect()->back()->with('success', 'All notifications marked as read');
        }

        return redirect()->back()->with('error', 'Admin not found');
    }
}
