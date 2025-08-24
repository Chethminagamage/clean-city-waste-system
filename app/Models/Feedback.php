<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];

    // Enable timestamps if your table has created_at and updated_at columns
    public $timestamps = true;

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
}