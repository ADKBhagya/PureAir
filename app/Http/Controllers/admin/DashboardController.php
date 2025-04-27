<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TriggeredAlert;
use App\Models\Sensor; // We also need sensors for active sensors count
use App\Models\SimulationSetting;

class DashboardController extends Controller
{
    public function index()
    {
        $alerts = TriggeredAlert::where('status', 'unread')->latest()->take(10)->get();
        $unreadCount = TriggeredAlert::where('status', 'unread')->count();
        $activeSensorsCount = Sensor::where('status', 'active')->count();
    
        // Fetch simulation status from SimulationSetting
        $simulationStatus = SimulationSetting::latest()->first()?->is_running ? 'Running' : 'Stopped';
    
        // Calculate average AQI
        $averageAQI = Sensor::where('status', 'active')->avg('aqi');
        $averageAQI = round($averageAQI);
    
        return view('pages.admin.dashboard', compact('alerts', 'unreadCount', 'activeSensorsCount', 'simulationStatus', 'averageAQI'));
    }
    
}
