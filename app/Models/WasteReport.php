<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteReport extends Model
{
    use HasFactory;

    protected $table = 'waste_reports';

    protected $fillable = [
        'resident_id',
        'location',
        'latitude',
        'longitude',
        'report_date',
        'image_path',
        'status',
        'waste_type',
        'additional_details',
    ];

    public function resident()
    {
        return $this->belongsTo(User::class, 'resident_id');
    }
}
