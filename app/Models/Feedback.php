<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    // Specify the table name if it's not the default 'feedbacks'
    protected $table = 'feedback';

    // Allow mass assignment for these fields
    protected $fillable = [
        'user_id',
        'feedback_type',
        'message',
    ];

    // Enable timestamps if your table has created_at and updated_at columns
    public $timestamps = true;

    // Optional: define relationship to user (if needed)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}