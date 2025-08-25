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
use App\Http\Controllers\Admin\BinReportController;
use App\Http\Controllers\Admin\PickupController;
use App\Http\Controllers\Admin\CollectorController;
use App\Http\Controllers\Collector\CollectorDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AlertController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Resident\ResidentProfileController;
use App\Http\Controllers\Resident\ResidentReportController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Resident\ReportHistoryController;
use App\Http\Controllers\NotificationController;
use Illuminate\Notifications\DatabaseNotification;
use App\Http\Controllers\Resident\CollectionScheduleController;

//Public Landing Page
Route::get('/', function () {
    return view('home');
})->name('landing.home');

/*
|--------------------------------------------------------------------------
| Email Verification for Residents
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'Verification link sent!');
    })->middleware('throttle:6,1')->name('verification.send');
});

Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = User::findOrFail($id);

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
    Route::get('/dashboard', [ResidentReportController::class, 'dashboard'])->name('dashboard');
    Route::post('/report/store', [ResidentReportController::class, 'store'])->name('report.store');
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.submit');

    Route::get('/profile/edit', [ResidentProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ResidentProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/remove-image', [ResidentProfileController::class, 'removeImage'])->name('profile.image.remove');
    Route::post('/toggle-2fa', [ResidentProfileController::class, 'toggle2FA'])->name('2fa.toggle');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/resident/dashboard', [\App\Http\Controllers\Resident\DashboardController::class, 'index'])
        ->name('resident.dashboard');
});

Route::middleware(['auth'])->group(function () {
    // Report routes
    Route::get('/resident/reports', [ReportHistoryController::class,'index'])->name('resident.reports.index');
    Route::get('/resident/reports/data', [ReportHistoryController::class,'data'])->name('resident.reports.data'); // filters+search JSON
    Route::post('/resident/reports/{report}/cancel', [ReportHistoryController::class,'cancel'])->name('resident.reports.cancel');
    Route::post('/resident/reports/{report}/duplicate', [ReportHistoryController::class,'duplicate'])->name('resident.reports.duplicate');
    Route::get('/resident/reports/export/csv', [ReportHistoryController::class,'exportCsv'])->name('resident.reports.export.csv');
    
    // Report Feedback Routes
    Route::get('/feedback/report/{reportId}', [FeedbackController::class, 'createForReport'])->name('feedback.report.create');
    Route::post('/feedback/report/{reportId}', [FeedbackController::class, 'storeForReport'])->name('feedback.report.store');
});

Route::get('/resident/reports/{report}', [ReportHistoryController::class,'show'])
    ->name('resident.reports.show');

Route::get('/resident/reports/{report}/pdf', [ReportHistoryController::class, 'pdf'])
    ->middleware('auth')        
    ->name('resident.reports.pdf');

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
    Route::get('/notifications/{notification}', [NotificationController::class, 'open'])
        ->name('notifications.open');
});

Route::middleware('auth')->group(function () {
    Route::get('/resident/schedule', [CollectionScheduleController::class, 'index'])
        ->name('resident.schedule.index');
});



/*
|--------------------------------------------------------------------------
| Two-Factor Authentication Routes (Modal Flow)
|--------------------------------------------------------------------------
*/
Route::post('/2fa/verify', [TwoFactorController::class, 'verifyOtp'])->name('2fa.verify');
Route::post('/2fa/resend', [TwoFactorController::class, 'resendOtp'])->name('2fa.resend');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('login.submit');

    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');
        Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/profile', [AdminProfileController::class, 'update'])->name('profile.update');

        Route::get('/binreports', [BinReportController::class, 'index'])->name('binreports');
        Route::get('/pickups', [PickupController::class, 'index'])->name('pickups');
        Route::get('/collectors', [CollectorController::class, 'index'])->name('collectors');
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::get('/alerts', [AlertController::class, 'index'])->name('alerts');
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
    });
});

/*
|--------------------------------------------------------------------------
| Collector Routes
|--------------------------------------------------------------------------
*/
Route::prefix('collector')->name('collector.')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'showCollectorLogin'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'loginCollector'])->name('login.submit');
    Route::post('/logout', [AuthenticatedSessionController::class, 'logoutCollector'])->name('logout');

    Route::middleware(['auth:collector'])->group(function () {
        Route::get('/dashboard', [CollectorDashboardController::class, 'index'])->name('dashboard');
        Route::post('/update-location', [CollectorDashboardController::class, 'updateLocation'])->name('updateLocation');
        Route::post('/report/{id}/collected', [CollectorDashboardController::class, 'markAsCollected'])->name('report.collected');
    });
});

Route::middleware(['auth', 'role:collector'])->group(function () {
    Route::patch('/collector/reports/{report}/collect',
        [\App\Http\Controllers\Collector\CollectorDashboardController::class, 'markAsCollected']
    )->name('collector.reports.collect');
});

Route::post('/collector/reports/{id}/start', [\App\Http\Controllers\Collector\CollectorDashboardController::class, 'startWork'])
    ->name('collector.reports.start');

Route::post('/collector/reports/{id}/collected', [\App\Http\Controllers\Collector\CollectorDashboardController::class, 'markAsCollected'])
    ->name('collector.reports.markCollected');

/*
|--------------------------------------------------------------------------
| Custom Email Verification (Optional)
|--------------------------------------------------------------------------
*/
Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['signed'])
    ->name('verification.verify');

/*
|--------------------------------------------------------------------------
| Laravel Breeze / Fortify Auth (Resident)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';