<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TriggeredAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'sensor_id',
        'pollutant_type',
        'threshold',
        'aqi_value',
        'status',
    ];
    
}
