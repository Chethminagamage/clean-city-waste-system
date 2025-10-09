<?php

namespace App\Observers;

use App\Models\BinReport;
use App\Services\ActivityLogService;

class BinReportObserver
{
    protected $activityLogger;

    public function __construct(ActivityLogService $activityLogger)
    {
        $this->activityLogger = $activityLogger;
    }

    /**
     * Handle the BinReport "created" event.
     */
    public function created(BinReport $binReport): void
    {
        $this->activityLogger->logModelChange(
            'create',
            $binReport,
            null,
            $binReport->toArray()
        );
    }

    /**
     * Handle the BinReport "updated" event.
     */
    public function updated(BinReport $binReport): void
    {
        $this->activityLogger->logModelChange(
            'update',
            $binReport,
            $binReport->getOriginal(),
            $binReport->getChanges()
        );
    }

    /**
     * Handle the BinReport "deleted" event.
     */
    public function deleted(BinReport $binReport): void
    {
        $this->activityLogger->logModelChange(
            'delete',
            $binReport,
            $binReport->toArray(),
            null
        );
    }

    /**
     * Handle the BinReport "restored" event.
     */
    public function restored(BinReport $binReport): void
    {
        //
    }

    /**
     * Handle the BinReport "force deleted" event.
     */
    public function forceDeleted(BinReport $binReport): void
    {
        //
    }
}
