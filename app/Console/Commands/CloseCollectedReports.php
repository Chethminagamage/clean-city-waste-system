<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\WasteReport;
use Carbon\Carbon;
use App\Notifications\ReportStatusUpdated;

class CloseCollectedReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:close-collected {--hours=24 : Hours since collection before closing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically closes waste reports that have been marked as collected for a specified period';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hoursThreshold = $this->option('hours');
        $thresholdTime = Carbon::now()->subHours($hoursThreshold);
        
        $this->info("Finding reports marked as collected before {$thresholdTime->format('Y-m-d H:i:s')}...");
        
        // Find reports to close - those marked as collected and updated before the threshold time
        $reports = WasteReport::where('status', WasteReport::ST_COLLECTED)
            ->where('updated_at', '<', $thresholdTime)
            ->get();
        
        $count = $reports->count();
        
        if ($count === 0) {
            $this->info("No reports found to close.");
            return 0;
        }
        
        $this->info("Found {$count} reports to close.");
        
        $progressBar = $this->output->createProgressBar($count);
        $progressBar->start();
        
        foreach ($reports as $report) {
            // Update report status
            $report->status = WasteReport::ST_CLOSED;
            $report->save();
            
            // Notify the resident if applicable
            if ($report->resident) {
                $report->resident->notify(new ReportStatusUpdated($report, WasteReport::ST_CLOSED));
            }
            
            $progressBar->advance();
        }
        
        $progressBar->finish();
        $this->newLine();
        $this->info("Successfully closed {$count} reports.");
        
        return 0;
    }
}
