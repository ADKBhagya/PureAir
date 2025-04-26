<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sensor;

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

        Sensor::create($validated);
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

        return redirect()->route('admin.sensor.management')->with('success', 'Sensor updated successfully!');
    }

    public function destroy($id)
    {
        Sensor::findOrFail($id)->delete();
        return back()->with('success', 'Sensor deleted successfully!');
    }
}
