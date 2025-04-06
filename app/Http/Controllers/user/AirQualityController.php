<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class AirQualityController extends Controller
{
    public function historicalData()
    {
        return view('pages.user.reports');
    }
}
