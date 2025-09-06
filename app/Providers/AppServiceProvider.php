<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\View\Composers\AdminNotificationComposer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register GamificationService as singleton
        $this->app->singleton(\App\Services\GamificationService::class);
        
        // Register refactored services and repositories
        $this->app->singleton(\App\Repositories\WasteReportRepository::class);
        $this->app->singleton(\App\Services\CollectorService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register view composer for admin notifications
        View::composer('admin.*', AdminNotificationComposer::class);
    }
}
