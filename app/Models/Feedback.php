<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Feedback extends Model
{
    use HasFactory;

    // Specify the table name if it's not the default 'feedbacks'
    protected $table = 'feedback';

    // Allow mass assignment for these fields
    protected $fillable = [
        'user_id',
        'waste_report_id',
        'feedback_type',
        'rating',
        'message',
        'admin_response',
        'admin_responded_by',
        'admin_responded_at',
        'status',
        'resolved_by',
        'resolved_at',
        'response_rating',
        'response_rated_at',
        'response_read_at',
        'subject',
        'type',
    ];

    // Enable timestamps if your table has created_at and updated_at columns
    public $timestamps = true;

    // Cast attributes to appropriate types
    protected $casts = [
        'admin_responded_at' => 'datetime',
        'resolved_at' => 'datetime',
        'response_rated_at' => 'datetime',
        'response_read_at' => 'datetime',
    ];

    // Define relationship to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define relationship to waste report
    public function wasteReport()
    {
        return $this->belongsTo(WasteReport::class);
    }

    // Define relationship to admin who responded
    public function adminRespondedBy()
    {
        return $this->belongsTo(Admin::class, 'admin_responded_by');
    }

    // Define relationship to admin who resolved
    public function resolvedBy()
    {
        return $this->belongsTo(Admin::class, 'resolved_by');
    }

    // Scopes for different feedback types
    public function scopeForReports($query)
    {
        return $query->whereNotNull('waste_report_id');
    }

    public function scopeGeneral($query)
    {
        return $query->whereNull('waste_report_id');
    }

    public function scopeLowRating($query)
    {
        return $query->where('rating', '<=', 2);
    }

    public function scopeHighRating($query)
    {
        return $query->where('rating', '>=', 4);
    }

    public function scopePendingResponse($query)
    {
        return $query->whereNull('admin_response');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    // Helper methods
    public function isLowRating()
    {
        return $this->rating <= 2;
    }

    public function needsAttention()
    {
        return $this->isLowRating() && !$this->admin_response;
    }

    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'resolved' => 'bg-green-100 text-green-800',
            'in_progress' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getRatingStars()
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }
}