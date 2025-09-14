<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;
    
    protected $fillable = ['name','code'];
    
    public function schedules() { return $this->hasMany(CollectionSchedule::class); }
    public function overrides() { return $this->hasMany(CollectionOverride::class); }
}
