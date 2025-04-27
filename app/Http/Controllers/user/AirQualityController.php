<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sensor; 
use App\Models\SensorReading; 
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class AirQualityController extends Controller
{
    public function airQualityPage()
    {
        $sensors = Sensor::where('status', 'active')->get(); // ✅ Get all active sensors
        return view('pages.user.airquality', compact('sensors')); // ✅ Pass $sensors to Blade
    }

    public function getSensors()
    {
        $sensors = Sensor::where('status', 'active')->get();
        return response()->json($sensors);
    }

    public function getSensorData($id)
    {
         $sensor = Sensor::findOrFail($id);

        $readings = SensorReading::where('sensor_id', $sensor->sensor_id)
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->pluck('aqi')
                    ->toArray();
    
        return response()->json([
            'location' => $sensor->location,
            'aqi' => $sensor->aqi,
            'category' => $this->getCategory($sensor->aqi),
            'updatedAgo' => Carbon::parse($sensor->updated_at)->diffForHumans(),
            'fullDate' => Carbon::parse($sensor->updated_at)->format('F d, Y h:i A'),
            'last_readings' => array_reverse($readings),
        ]);
    }

    private function getCategory($aqi)
    {
        if ($aqi <= 50) return 'Good';
        if ($aqi <= 100) return 'Moderate';
        if ($aqi <= 150) return 'Unhealthy for Sensitive Groups';
        if ($aqi <= 200) return 'Unhealthy';
        if ($aqi <= 300) return 'Very Unhealthy';
        return 'Hazardous';
    }

    public function historicalData()
    {
        // Fetch all active sensors
        $sensors = Sensor::where('status', 'active')->get();

        // Pass them to the view
        return view('pages.user.reports', compact('sensors'));
    }

    // Fetch the historical data for a specific sensor
    public function fetchHistoricalData(Request $request)
    {
        $sensorId = $request->query('sensor');
        $range = $request->query('range');

        if (!$sensorId || !$range) {
            return response()->json(['error' => 'Missing parameters'], 400);
        }

        $query = DB::table('sensor_readings')
            ->where('sensor_id', $sensorId);

        $now = Carbon::now();

        if ($range === 'day') {
            $query->where('created_at', '>=', $now->subDay());
        } elseif ($range === 'week') {
            $query->where('created_at', '>=', $now->subWeek());
        } elseif ($range === 'month') {
            $query->where('created_at', '>=', $now->subMonth());
        }

        $readings = $query
            ->orderBy('created_at', 'asc')
            ->get(['created_at', 'aqi']);

        return response()->json($readings);
    }
}
