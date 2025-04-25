<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorReading extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'sensor_id',
        'aqi',
        'created_at',
    ];

    protected $dates = ['created_at'];
}
