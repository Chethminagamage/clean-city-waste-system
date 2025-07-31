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
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AlertController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Resident\ResidentProfileController;
use App\Http\Controllers\Resident\ResidentReportController;

/*
|--------------------------------------------------------------------------
| Public Landing Page
|--------------------------------------------------------------------------
*/
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
Route::middleware(['auth', 'role:resident'])->prefix('resident')->group(function () {
    // Dashboard
    Route::get('/dashboard', [ResidentReportController::class, 'dashboard'])->name('resident.dashboard');

    // Report Submission
    Route::post('/report/store', [ResidentReportController::class, 'store'])->name('resident.report.store');

    // Feedback
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.submit');

    // Profile
    Route::middleware(['auth'])->prefix('resident')->group(function () {
    Route::get('/profile/edit', [ResidentProfileController::class, 'edit'])->name('resident.profile.edit');
    Route::post('/profile/update', [ResidentProfileController::class, 'update'])->name('resident.profile.update');
    Route::post('/profile/remove-image', [ResidentProfileController::class, 'removeImage'])->name('resident.profile.image.remove');
});
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    // Login
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('login.submit');

    // Protected Admin Panel Routes
    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/profile', [AdminProfileController::class, 'update'])->name('profile.update');

        Route::get('/binreports', [BinReportController::class, 'index'])->name('binreports');
        Route::get('/pickups', [PickupController::class, 'index'])->name('pickups');
        Route::get('/collectors', [CollectorController::class, 'index'])->name('collectors');
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::get('/alerts', [AlertController::class, 'index'])->name('alerts');
        Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');

        Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');
    });
});

/*
|--------------------------------------------------------------------------
| Collector Routes
|--------------------------------------------------------------------------
*/
Route::prefix('collector')->name('collector.')->group(function () {
    // Collector Login
    Route::get('/login', [AuthenticatedSessionController::class, 'showCollectorLogin'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'loginCollector'])->name('login.submit');

    // Collector Dashboard
    Route::middleware(['auth', 'role:collector'])->group(function () {
        Route::get('/dashboard', function () {
            return view('collector.dashboard');
        })->name('dashboard');
    });
});

/*
|--------------------------------------------------------------------------
| Optional Custom Email Verification
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