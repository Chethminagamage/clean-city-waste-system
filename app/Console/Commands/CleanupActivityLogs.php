<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ActivityLog;
use App\Services\ActivityLogService;
use Carbon\Carbon;

class CleanupActivityLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activity-logs:cleanup 
                          {--days=90 : Number of days to keep logs}
                          {--keep-critical : Keep critical security events}
                          {--dry-run : Show what would be deleted without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old activity logs while preserving critical security events';

    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        parent::__construct();
        $this->activityLogService = $activityLogService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $keepCritical = $this->option('keep-critical');
        $dryRun = $this->option('dry-run');
        
        $cutoffDate = Carbon::now()->subDays($days);
        
        $this->info("Activity Log Cleanup - Retention Policy: {$days} days");
        $this->info("Cutoff Date: {$cutoffDate->format('Y-m-d H:i:s')}");
        
        if ($dryRun) {
            $this->warn('DRY RUN MODE - No actual deletions will be performed');
        }
        
        // Build query for logs to delete
        $query = ActivityLog::where('created_at', '<', $cutoffDate);
        
        if ($keepCritical) {
            // Exclude critical security events from deletion
            $query->where(function($q) {
                $q->where('severity', '!=', ActivityLog::SEVERITY_CRITICAL)
                  ->orWhere(function($subQ) {
                      $subQ->whereNotIn('activity_category', [
                          ActivityLog::CATEGORY_SECURITY,
                          ActivityLog::CATEGORY_AUTHENTICATION
                      ]);
                  });
            });
            
            $this->info('Preserving critical security events regardless of age');
        }
        
        $totalLogs = ActivityLog::count();
        $logsToDelete = $query->count();
        $logsToKeep = $totalLogs - $logsToDelete;
        
        $this->table([
            'Metric',
            'Count'
        ], [
            ['Total logs in database', number_format($totalLogs)],
            ['Logs to be deleted', number_format($logsToDelete)],
            ['Logs to be kept', number_format($logsToKeep)],
            ['Space savings estimate', $this->estimateSpaceSavings($logsToDelete)]
        ]);
        
        if ($logsToDelete === 0) {
            $this->info('No logs found for cleanup.');
            return 0;
        }
        
        if ($dryRun) {
            $this->info('Dry run complete. Run without --dry-run to perform actual cleanup.');
            return 0;
        }
        
        if (!$this->confirm("Are you sure you want to delete {$logsToDelete} activity logs?")) {
            $this->info('Cleanup cancelled.');
            return 0;
        }
        
        $this->info('Starting cleanup process...');
        
        // Perform cleanup in batches to avoid memory issues
        $batchSize = 1000;
        $deleted = 0;
        $progressBar = $this->output->createProgressBar($logsToDelete);
        
        while ($deleted < $logsToDelete) {
            $batch = clone $query;
            $batchLogs = $batch->limit($batchSize)->get();
            
            if ($batchLogs->isEmpty()) {
                break;
            }
            
            foreach ($batchLogs as $log) {
                $log->delete();
                $deleted++;
                $progressBar->advance();
            }
        }
        
        $progressBar->finish();
        $this->line('');
        
        // Log the cleanup operation
        $this->activityLogService->logSystemEvent(
            'cleanup',
            "Cleaned up {$deleted} activity logs older than {$days} days",
            ActivityLog::SEVERITY_LOW,
            [
                'deleted_count' => $deleted,
                'retention_days' => $days,
                'keep_critical' => $keepCritical,
                'cutoff_date' => $cutoffDate->toDateTimeString()
            ]
        );
        
        $this->info("✅ Successfully deleted {$deleted} activity logs");
        $this->info("Cleanup completed at " . now()->format('Y-m-d H:i:s'));
        
        // Show final statistics
        $remainingLogs = ActivityLog::count();
        $this->info("Remaining logs in database: " . number_format($remainingLogs));
        
        return 0;
    }
    
    /**
     * Estimate space savings from cleanup
     */
    private function estimateSpaceSavings($logCount)
    {
        // Estimate ~2KB per log entry (very rough estimate)
        $bytesPerLog = 2048;
        $totalBytes = $logCount * $bytesPerLog;
        
        if ($totalBytes < 1024 * 1024) {
            return number_format($totalBytes / 1024, 1) . ' KB';
        } elseif ($totalBytes < 1024 * 1024 * 1024) {
            return number_format($totalBytes / (1024 * 1024), 1) . ' MB';
        } else {
            return number_format($totalBytes / (1024 * 1024 * 1024), 2) . ' GB';
        }
    }
}
