<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sensor;

class SensorController extends Controller
{
    // Show all sensors to the Blade view
    public function index()
    {
        $sensors = Sensor::all();
        return view('pages.admin.sensor-management', compact('sensors'));
    }

    // Store new sensor
    public function store(Request $request)
    {
        $request->validate([
            'sensor_id' => 'required|unique:sensors,sensor_id',
            'location' => 'required',
            'aqi' => 'required|integer',
            'status' => 'required|in:active,inactive',
        ]);

        Sensor::create($request->all());

        return redirect()->back()->with('success', 'Sensor added successfully.');
    }

    // Update sensor
    public function update(Request $request, $id)
    {
        $sensor = Sensor::findOrFail($id);

        $request->validate([
            'sensor_id' => 'required|unique:sensors,sensor_id,' . $sensor->id,
            'location' => 'required',
            'aqi' => 'required|integer',
            'status' => 'required|in:active,inactive',
        ]);

        $sensor->update($request->all());

        return redirect()->back()->with('success', 'Sensor updated successfully.');
    }

    // Delete sensor
    public function destroy($id)
    {
        $sensor = Sensor::findOrFail($id);
        $sensor->delete();

        return redirect()->back()->with('success', 'Sensor deleted.');
    }

    // Optional: For API (fetching as JSON)
    public function apiIndex()
    {
        return Sensor::all();
    }
}
