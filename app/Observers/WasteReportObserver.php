<?php

namespace App\Observers;

use App\Models\WasteReport;
use App\Services\ActivityLogService;

class WasteReportObserver
{
    protected $activityLogger;

    public function __construct(ActivityLogService $activityLogger)
    {
        $this->activityLogger = $activityLogger;
    }

    /**
     * Handle the WasteReport "created" event.
     */
    public function created(WasteReport $wasteReport): void
    {
        $this->activityLogger->logModelChange(
            'create',
            $wasteReport,
            null,
            $wasteReport->toArray()
        );
    }

    /**
     * Handle the WasteReport "updated" event.
     */
    public function updated(WasteReport $wasteReport): void
    {
        $this->activityLogger->logModelChange(
            'update',
            $wasteReport,
            $wasteReport->getOriginal(),
            $wasteReport->getChanges()
        );
    }

    /**
     * Handle the WasteReport "deleted" event.
     */
    public function deleted(WasteReport $wasteReport): void
    {
        $this->activityLogger->logModelChange(
            'delete',
            $wasteReport,
            $wasteReport->toArray(),
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
