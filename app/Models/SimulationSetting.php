<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimulationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'frequency',
        'baseline',
        'variation',
        'is_running',
    ];
}
