<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollectionOverride extends Model
{
    protected $fillable = ['area_id','date','kind','start_time','end_time','waste_type','reason'];
    public function area() { return $this->belongsTo(Area::class); }
}
