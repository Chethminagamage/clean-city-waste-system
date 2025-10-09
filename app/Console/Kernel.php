<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Activity log cleanup - run daily at 2 AM
        $schedule->command('activity-logs:cleanup --days=90 --keep-critical')
                 ->daily()
                 ->at('02:00')
                 ->name('activity-logs-cleanup')
                 ->description('Clean up old activity logs while preserving critical events');
        
        // Weekly security report (could be added later)
        // $schedule->command('security:weekly-report')
        //          ->weekly()
        //          ->sundays()
        //          ->at('08:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}