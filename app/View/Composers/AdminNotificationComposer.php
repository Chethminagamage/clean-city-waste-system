<?php

namespace App\View\Composers;

use Illuminate\View\View;

class AdminNotificationComposer
{
    public function compose(View $view)
    {
        if (auth('admin')->check()) {
            $admin = auth('admin')->user();
            $unreadNotifications = $admin->unreadNotifications()->count();
            $urgentNotifications = $admin->unreadNotifications()
                ->where('type', 'App\Notifications\UrgentBinReport')
                ->count();
                
            $view->with([
                'unreadNotificationsCount' => $unreadNotifications,
                'urgentNotificationsCount' => $urgentNotifications
            ]);
        }
    }
}
