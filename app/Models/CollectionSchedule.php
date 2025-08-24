<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollectionSchedule extends Model {
    protected $fillable = ['area_id','date','waste_type','window_from','window_to','notes', 'frequency'];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'date',
    ];
    
    public function area(){ return $this->belongsTo(Area::class); }
}
