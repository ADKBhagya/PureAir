<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlertRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'pollutant_type',
        'threshold',
        'check_frequency',
        'alert_type',
    ];
}
