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
    public const ST_ENROUTE     = 'enroute';
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
        'reference_code',
        'location',
        'latitude',
        'longitude',
        'report_date',
        'image_path',
        'status',
        'waste_type',
        'additional_details',
        'admin_notes',
        'is_urgent',
        'urgent_reported_at',
        'urgent_message',
    ];

    /**
     * Helpful casting
     */
    protected $casts = [
        'latitude'   => 'float',
        'longitude'  => 'float',
        'report_date'=> 'datetime',
        'status'     => 'string',
        'is_urgent'  => 'boolean',
        'urgent_reported_at' => 'datetime',
    ];

    /**
     * Boot the model to auto-generate reference codes
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($report) {
            if (empty($report->reference_code)) {
                $report->reference_code = self::generateReferenceCode();
            }
        });
    }

    /**
     * Generate a unique reference code for the report
     */
    public static function generateReferenceCode()
    {
        do {
            $code = 'WR-' . strtoupper(substr(uniqid(), -8));
        } while (self::where('reference_code', $code)->exists());

        return $code;
    }

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
            ->whereIn('status', [self::ST_ASSIGNED, self::ST_ENROUTE]);
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
    
    /**
     * Check if report can be marked as urgent (bin full)
     * Only allow after 4 hours and if not already urgent, collected, or closed
     */
    public function canBeMarkedUrgent(): bool
    {
        // Don't allow if already urgent
        if ($this->is_urgent) {
            return false;
        }
        
        // Don't allow if already collected or closed
        if (in_array($this->status, [self::ST_COLLECTED, self::ST_CLOSED])) {
            return false;
        }
        
        // Only allow after 4 hours
        return $this->created_at->addHours(4)->isPast();
    }
    
    /**
     * Mark this report as urgent (bin full)
     */
    public function markAsUrgent(string $message = 'My bin is full - needs urgent collection'): bool
    {
        // Only allow marking as urgent if not already collected or closed
        if (in_array($this->status, [self::ST_COLLECTED, self::ST_CLOSED])) {
            return false;
        }
        
        // Only allow one urgent notification per report
        if ($this->is_urgent) {
            return false;
        }
        
        $this->update([
            'is_urgent' => true,
            'urgent_reported_at' => now(),
            'urgent_message' => $message,
        ]);
        
        return true;
    }
}