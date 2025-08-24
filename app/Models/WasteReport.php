<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteReport extends Model
{
    use HasFactory;

    /**
     * Single source of truth for statuses
     */
    public const ST_PENDING     = 'pending';
    public const ST_ASSIGNED    = 'assigned';
    public const ST_IN_PROGRESS = 'in_progress';
    public const ST_COLLECTED   = 'collected';
    public const ST_CLOSED      = 'closed';
    public const ST_DECLINED    = 'declined';

    protected $table = 'waste_reports';

    /**
     * Make sure all the columns you actually write to are fillable.
     * (Added collector_id — it was missing before.)
     */
    protected $fillable = [
        'resident_id',
        'collector_id',
        'location',
        'latitude',
        'longitude',
        'report_date',
        'image_path',
        'status',
        'waste_type',
        'additional_details',
    ];

    /**
     * Helpful casting
     */
    protected $casts = [
        'latitude'   => 'float',
        'longitude'  => 'float',
        'report_date'=> 'datetime',
        'status'     => 'string',
    ];

    /**
     * Always store status in lowercase to avoid mismatches like "Assigned" vs "assigned"
     */
    protected function status(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => is_string($value) ? strtolower($value) : $value
        );
    }

    /**
     * Relationships
     */
    public function resident()
    {
        return $this->belongsTo(User::class, 'resident_id');
    }

    public function collector()
    {
        return $this->belongsTo(User::class, 'collector_id');
    }

    /**
     * Scopes commonly used by dashboards
     */
    public function scopeForCollector($query, int $collectorId)
    {
        return $query->where('collector_id', $collectorId);
    }

    public function scopeActiveForCollector($query, int $collectorId)
    {
        return $query->forCollector($collectorId)
            ->whereIn('status', [self::ST_ASSIGNED, self::ST_IN_PROGRESS]);
    }

    public function scopeCompletedForCollector($query, int $collectorId)
    {
        return $query->forCollector($collectorId)
            ->whereIn('status', [self::ST_COLLECTED, self::ST_CLOSED]);
    }

    public function scopeForResident($query, int $residentId)
    {
        return $query->where('resident_id', $residentId);
    }

    /**
     * Optional convenience helpers
     */
    public function isCollected(): bool
    {
        return $this->status === self::ST_COLLECTED;
    }
    
    public function isClosed(): bool
    {
        return $this->status === self::ST_CLOSED;
    }
    
    /**
     * Get feedback associated with this waste report
     */
    public function feedback()
    {
        return $this->hasOne(Feedback::class);
    }
    
    /**
     * Check if report has feedback
     */
    public function hasFeedback(): bool
    {
        return $this->feedback()->exists();
    }
}