<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sensor;
use App\Models\SensorReading;

class SensorController extends Controller
{
    public function index()
    {
        $sensors = Sensor::all();
        return view('pages.admin.sensor-management', compact('sensors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sensor_id' => 'required|unique:sensors',
            'location' => 'required|string',
            'aqi' => 'required|integer',
            'status' => 'required|in:active,inactive',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $sensor = Sensor::create($validated);

        // ✅ Save full reading with location, status, lat, lng
        SensorReading::create([
            'sensor_id' => $sensor->sensor_id,
            'location' => $sensor->location,
            'status' => $sensor->status,
            'lat' => $sensor->lat,
            'lng' => $sensor->lng,
            'aqi' => $sensor->aqi,
            'created_at' => now(),
        ]);

        \Artisan::call('check:sensor-alerts');

        return redirect()->route('admin.sensor.management')->with('success', 'Sensor added successfully!');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'sensor_id' => 'required|string',
            'location' => 'required|string',
            'aqi' => 'required|integer',
            'status' => 'required|in:active,inactive',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $sensor = Sensor::findOrFail($id);
        $sensor->update($validated);

        \Artisan::call('check:sensor-alerts');

        return redirect()->route('admin.sensor.management')->with('success', 'Sensor updated successfully!');
    }

    public function destroy($id)
    {
        Sensor::findOrFail($id)->delete();
        return back()->with('success', 'Sensor deleted successfully!');
    }

    public function sensorHistory($sensorId)
    {
        $readings = SensorReading::where('sensor_id', $sensorId)
            ->where('created_at', '>=', now()->subDay())
            ->orderBy('created_at', 'asc')
            ->get(['aqi', 'created_at']);

        return response()->json($readings);
    }
}
