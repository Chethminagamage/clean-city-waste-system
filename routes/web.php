
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use App\Models\User;

// Controllers
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\Auth\TwoFactorController as AdminTwoFactorController;

use App\Http\Controllers\Admin\BinReportController;
use App\Http\Controllers\Admin\CollectorController;
use App\Http\Controllers\Collector\CollectorDashboardController;
use App\Http\Controllers\Collector\CollectorNotificationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AlertController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Resident\ResidentProfileController;
use App\Http\Controllers\Resident\ResidentReportController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Resident\ReportHistoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ResidentFeedbackController;
use Illuminate\Notifications\DatabaseNotification;
use App\Http\Controllers\Resident\CollectionScheduleController;
use App\Http\Controllers\PublicPagesController;

//Public Landing Page
Route::get('/', function () {
    return view('home');
})->name('landing.home');

// Public Pages
Route::get('/services', [PublicPagesController::class, 'services'])->name('public.services');
Route::get('/projects', [PublicPagesController::class, 'projects'])->name('public.projects');
Route::get('/company', [PublicPagesController::class, 'company'])->name('public.company');
Route::get('/blog', [PublicPagesController::class, 'blog'])->name('public.blog');
Route::get('/contact', [PublicPagesController::class, 'contact'])->name('public.contact');

/*
|--------------------------------------------------------------------------
| Activity Tracking Routes (Auto-logout)
|--------------------------------------------------------------------------
*/
Route::middleware(['web'])->group(function () {
    Route::post('/activity-ping', [App\Http\Controllers\ActivityController::class, 'ping'])->name('activity.ping');
    Route::get('/activity-status', [App\Http\Controllers\ActivityController::class, 'status'])->name('activity.status');
    Route::post('/activity-extend', [App\Http\Controllers\ActivityController::class, 'extend'])->name('activity.extend');
});

/*
|--------------------------------------------------------------------------
| Universal Profile Route (redirects based on user role)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->get('/profile', function () {
    $user = auth()->user();
    
    switch ($user->role) {
        case 'admin':
            return redirect('/admin/profile');
        case 'resident':
        default:
            return redirect('/resident/profile/edit');
    }
})->name('profile');

/*
|--------------------------------------------------------------------------
| Email Verification (Manual Implementation - Residents Only)
|--------------------------------------------------------------------------
*/
Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = User::findOrFail($id);

    // ✅ Only verify resident accounts
    if ($user->role !== 'resident') {
        abort(403, 'This verification link is only for resident accounts.');
    }

    if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        abort(403, 'Invalid verification link.');
    }

    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new Verified($user));
    }

    return redirect('/login')->with('status', 'Email verified successfully! You may now log in.');
})->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

/*
|--------------------------------------------------------------------------
| Resident Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:resident'])->prefix('resident')->name('resident.')->group(function () {
    Route::get('/dashboard', [ResidentReportController::class, 'dashboard'])->name('dashboard.main');
    Route::post('/report/store', [ResidentReportController::class, 'store'])->name('report.store');
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.submit');

    Route::get('/profile/edit', [ResidentProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ResidentProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/remove-image', [ResidentProfileController::class, 'removeImage'])->name('profile.image.remove');
    Route::post('/toggle-2fa', [ResidentProfileController::class, 'toggle2FA'])->name('2fa.toggle');
});

// Additional resident routes - properly secured with role-based middleware
Route::middleware(['auth', 'role:resident'])->group(function () {
    Route::get('/resident/dashboard', [\App\Http\Controllers\Resident\DashboardController::class, 'index'])
        ->name('resident.dashboard');
        
    // Report routes
    Route::get('/resident/reports', [ReportHistoryController::class,'index'])->name('resident.reports.index');
    Route::get('/resident/reports/data', [ReportHistoryController::class,'data'])->name('resident.reports.data'); // filters+search JSON
    Route::post('/resident/reports/{report}/cancel', [ReportHistoryController::class,'cancel'])->name('resident.reports.cancel');
    Route::post('/resident/reports/{report}/duplicate', [ReportHistoryController::class,'duplicate'])->name('resident.reports.duplicate');
    Route::get('/resident/reports/export/csv', [ReportHistoryController::class,'exportCsv'])->name('resident.reports.export.csv');
    
    // Report Feedback Routes
    Route::get('/feedback/report/{reportId}', [FeedbackController::class, 'createForReport'])->name('feedback.report.create');
    Route::post('/feedback/report/{reportId}', [FeedbackController::class, 'storeForReport'])->name('feedback.report.store');
    
    // General feedback routes
    Route::get('/feedback', [FeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
    
    // Resident feedback management routes
    Route::get('/resident/feedback', [ResidentFeedbackController::class, 'index'])->name('resident.feedback.index');
    Route::get('/resident/feedback/{id}', [ResidentFeedbackController::class, 'show'])->name('resident.feedback.show');
    Route::post('/resident/feedback/mark-responses-read', [ResidentFeedbackController::class, 'markResponsesRead'])->name('resident.feedback.mark_responses_read');
    Route::post('/resident/feedback/{id}/rate-response', [ResidentFeedbackController::class, 'rateResponse'])->name('resident.feedback.rate_response');
    
    // Individual report view and PDF
    Route::get('/resident/reports/{report}', [ReportHistoryController::class,'show'])
        ->name('resident.reports.show');
    Route::get('/resident/reports/{report}/pdf', [ReportHistoryController::class, 'pdf'])
        ->name('resident.reports.pdf');
    Route::post('/resident/reports/{report}/urgent', [ResidentReportController::class, 'markUrgent'])
        ->name('resident.reports.urgent');
});

Route::middleware('auth')->group(function () {
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])
        ->name('notifications.index');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markRead'])
        ->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])
        ->name('notifications.readAll');
    Route::get('/notifications/{notification}', [NotificationController::class, 'show'])
        ->name('notifications.show')
        ->whereUuid('notification'); 
    Route::get('/notifications/{notification}/open', [NotificationController::class, 'open'])
        ->name('notifications.open');
});

// Theme Management Routes (accessible by both auth guards)
Route::middleware('auth')->group(function () {
    Route::post('/theme/toggle', [\App\Http\Controllers\ThemeController::class, 'toggle'])
        ->name('theme.toggle');
    Route::post('/theme/set', [\App\Http\Controllers\ThemeController::class, 'setTheme'])
        ->name('theme.set');
    Route::get('/theme/current', [\App\Http\Controllers\ThemeController::class, 'getTheme'])
        ->name('theme.current');
});

Route::middleware(['auth', 'role:resident'])->group(function () {
    Route::get('/resident/schedule', [CollectionScheduleController::class, 'index'])
        ->name('resident.schedule.index');
});

// Gamification Routes (Simplified)
Route::middleware(['auth', 'role:resident'])->prefix('resident')->name('resident.')->group(function () {
    Route::get('/gamification', [\App\Http\Controllers\Resident\GamificationController::class, 'index'])
        ->name('gamification.index');
    Route::get('/rewards', [\App\Http\Controllers\Resident\GamificationController::class, 'rewards'])
        ->name('gamification.rewards');
    Route::post('/rewards/{reward}/redeem', [\App\Http\Controllers\Resident\GamificationController::class, 'redeemReward'])
        ->name('gamification.redeem');
    
    // API endpoint for AJAX requests
    Route::get('/api/gamification-stats', [\App\Http\Controllers\Resident\GamificationController::class, 'apiStats'])
        ->name('gamification.api.stats');
});

/*
|--------------------------------------------------------------------------
| Two-Factor Authentication Routes (Modal Flow)
|--------------------------------------------------------------------------
*/
Route::post('/2fa/verify', [TwoFactorController::class, 'verifyOtp'])->name('2fa.verify')->middleware('throttle:10,1'); // 10 2FA attempts per minute
Route::post('/2fa/resend', [TwoFactorController::class, 'resendOtp'])->name('2fa.resend')->middleware('throttle:3,1'); // 3 2FA resend attempts per minute

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('login.submit')->middleware('throttle:5,1'); // 5 admin login attempts per minute

    // Password Reset Routes
    Route::get('/forgot-password', [App\Http\Controllers\Admin\Auth\PasswordResetController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [App\Http\Controllers\Admin\Auth\PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email')->middleware('throttle:3,1'); // 3 reset attempts per minute
    Route::get('/reset-password/{token}', [App\Http\Controllers\Admin\Auth\PasswordResetController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [App\Http\Controllers\Admin\Auth\PasswordResetController::class, 'resetPassword'])->name('password.update')->middleware('throttle:5,1'); // 5 reset attempts per minute

    // Two-Factor Authentication Routes
    Route::get('/2fa/verify', [AdminTwoFactorController::class, 'showVerifyForm'])->name('2fa.verify');
    Route::post('/2fa/verify', [AdminTwoFactorController::class, 'verify'])->name('2fa.verify.submit');
    
    // 2FA Management Routes (require authentication)
    Route::middleware(['auth:admin'])->group(function () {

    });

    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard.main');
        Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile/photo', [AdminProfileController::class, 'removePhoto'])->name('profile.remove-photo');
        
        // 2FA Routes for Profile
        Route::post('/profile/2fa/enable', [AdminProfileController::class, 'enableTwoFactor'])->name('profile.2fa.enable');
        Route::post('/profile/2fa/disable', [AdminProfileController::class, 'disableTwoFactor'])->name('profile.2fa.disable');

        Route::get('/binreports', [BinReportController::class, 'index'])->name('binreports');
        Route::get('/collectors', [CollectorController::class, 'index'])->name('collectors');
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::get('/alerts', [AlertController::class, 'index'])->name('alerts');
        Route::post('/alerts/{notification}/mark-read', [AlertController::class, 'markAsRead'])->name('alerts.markRead');
        Route::post('/alerts/mark-all-read', [AlertController::class, 'markAllAsRead'])->name('alerts.markAllRead');
        Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
        
        // Collection Schedules CRUD
        Route::resource('schedules', \App\Http\Controllers\Admin\CollectionScheduleController::class);
        Route::post('/schedules/generate', [\App\Http\Controllers\Admin\CollectionScheduleController::class, 'generateSchedules'])->name('schedules.generate');

        Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

        Route::get('/users', [AdminUserController::class, 'index'])->name('users');
        Route::post('/users/{id}/toggle', [AdminUserController::class, 'toggleStatus'])->name('users.toggle');
        Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('users.delete');

        Route::post('/collectors/store', [CollectorController::class, 'store'])->name('collectors.store');
        Route::post('/collectors/update/{collector}', [CollectorController::class, 'update'])->name('collectors.update');
        Route::post('/collectors/toggle-status/{collector}', [CollectorController::class, 'toggleStatus'])->name('collectors.toggle');
        Route::delete('/collectors/delete/{collector}', [CollectorController::class, 'destroy'])->name('collectors.delete');

        Route::post('/binreports/assign/{reportId}', [BinReportController::class, 'assignNearestCollector'])->name('assign.collector'); // auto
        Route::get('/report/{id}/nearby-collectors', [BinReportController::class, 'getNearbyCollectors'])->name('report.nearby.collectors'); // ajax
        Route::post('/assign-collector/{report}', [BinReportController::class, 'assignCollector'])->name('report.assign.collector'); // manual
        Route::post('/reports/{id}/close', [BinReportController::class, 'closeReport'])->name('reports.close'); // close collected report
        Route::get('/reports/{id}', [BinReportController::class, 'show'])->name('reports.show'); // view report details
        Route::post('/reports/{id}/cancel', [BinReportController::class, 'cancelReport'])->name('reports.cancel'); // cancel report
        Route::post('/reports/{id}/add-note', [BinReportController::class, 'addNote'])->name('reports.add_note'); // add admin note
        
        // Feedback Management Routes
        Route::get('/feedback', [\App\Http\Controllers\Admin\FeedbackController::class, 'index'])->name('feedback.index');
        Route::get('/feedback/export', [\App\Http\Controllers\Admin\FeedbackController::class, 'export'])->name('feedback.export');
        Route::get('/feedback/{id}', [\App\Http\Controllers\Admin\FeedbackController::class, 'show'])->name('feedback.show');
        Route::post('/feedback/{id}/respond', [\App\Http\Controllers\Admin\FeedbackController::class, 'respond'])->name('feedback.respond');
        Route::post('/feedback/{id}/resolve', [\App\Http\Controllers\Admin\FeedbackController::class, 'markResolved'])->name('feedback.resolve');
        
        // Audit Trail Routes
        Route::get('/audit-trail', [\App\Http\Controllers\Admin\AuditTrailController::class, 'index'])->name('audit-trail.index');
        Route::get('/audit-trail/export', [\App\Http\Controllers\Admin\AuditTrailController::class, 'export'])->name('audit-trail.export');
        Route::get('/audit-trail/{id}', [\App\Http\Controllers\Admin\AuditTrailController::class, 'show'])->name('audit-trail.show');
        Route::get('/security-events', [\App\Http\Controllers\Admin\AuditTrailController::class, 'securityEvents'])->name('security-events');
        Route::get('/suspicious-activities', [\App\Http\Controllers\Admin\AuditTrailController::class, 'suspiciousActivities'])->name('suspicious-activities');
    });
});

/*
|--------------------------------------------------------------------------
| Collector Routes
|--------------------------------------------------------------------------
*/
Route::prefix('collector')->name('collector.')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'showCollectorLogin'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'loginCollector'])->name('login.submit')->middleware('throttle:5,1'); // 5 collector login attempts per minute
    Route::post('/logout', [AuthenticatedSessionController::class, 'logoutCollector'])->name('logout');
    
    // Collector Password Reset Routes
    Route::get('/forgot-password', [\App\Http\Controllers\Collector\Auth\CollectorPasswordResetLinkController::class, 'create'])
                ->middleware('guest')
                ->name('password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\Collector\Auth\CollectorPasswordResetLinkController::class, 'store'])
                ->middleware('guest')
                ->middleware('throttle:3,1') // 3 password reset attempts per minute
                ->name('password.email');
    Route::get('/reset-password/{token}', [\App\Http\Controllers\Collector\Auth\CollectorNewPasswordController::class, 'create'])
                ->middleware('guest')
                ->name('password.reset');
    Route::post('/reset-password', [\App\Http\Controllers\Collector\Auth\CollectorNewPasswordController::class, 'store'])
                ->middleware('guest')
                ->middleware('throttle:3,1') // 3 password reset completions per minute
                ->name('password.store');

    // Register dashboard route as collector.dashboard
    Route::get('/dashboard', [CollectorDashboardController::class, 'index'])
        ->middleware('auth:collector')
        ->name('dashboard');

    // Alias route for collector.dashboard.main (redirect to dashboard)
    Route::get('/dashboard-main-alias', function() {
        return redirect()->route('collector.dashboard');
    })->middleware('auth:collector')->name('dashboard.main');
    // Continue with other routes as before
    Route::middleware(['auth:collector'])->group(function () {
        Route::get('/profile', [CollectorDashboardController::class, 'profile'])->name('profile');
        Route::put('/profile', [CollectorDashboardController::class, 'updateProfile'])->name('profile.update');
        Route::put('/password', [CollectorDashboardController::class, 'updatePassword'])->name('password.update');
        Route::put('/profile/picture', [CollectorDashboardController::class, 'updateProfilePicture'])->name('profile.picture');
        Route::delete('/profile/picture', [CollectorDashboardController::class, 'removeProfilePicture'])->name('profile.picture.remove');
        Route::get('/reports', [CollectorDashboardController::class, 'allReports'])->name('reports.all');
        Route::get('/reports/completed', [CollectorDashboardController::class, 'completedReports'])->name('reports.completed');
        Route::get('/report/{id}/details', [CollectorDashboardController::class, 'reportDetails'])->name('report.details');
        Route::get('/report/{id}', [CollectorDashboardController::class, 'showReportDetails'])->name('report.show');
        Route::post('/update-location', [CollectorDashboardController::class, 'updateLocation'])->name('updateLocation');
        Route::post('/report/{id}/confirm', [CollectorDashboardController::class, 'confirmAssignment'])->name('report.confirm');
        Route::post('/report/{id}/start', [CollectorDashboardController::class, 'startWork'])->name('report.start');
        Route::post('/report/{id}/complete-with-image', [CollectorDashboardController::class, 'completeWithImage'])->name('report.complete-with-image');
        
        // Notification routes
        Route::get('/notifications', [CollectorNotificationController::class, 'index'])->name('notifications.index');
        Route::get('/notifications/recent', [CollectorNotificationController::class, 'getRecent'])->name('notifications.recent');
        Route::get('/notifications/unread-count', [CollectorNotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
        Route::post('/notifications/{id}/mark-read', [CollectorNotificationController::class, 'markAsRead'])->name('notifications.mark-read');
        Route::post('/notifications/mark-all-read', [CollectorNotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
        Route::get('/notifications/{notification}', [CollectorNotificationController::class, 'show'])->name('notifications.show');
        Route::delete('/notifications/{id}', [CollectorNotificationController::class, 'destroy'])->name('notifications.destroy');
    });
});

/*
|--------------------------------------------------------------------------
| Laravel Breeze / Fortify Auth (Resident)
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Chat & Communication Routes
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';