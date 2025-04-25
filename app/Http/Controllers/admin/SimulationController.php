<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SimulationSetting;

class SimulationController extends Controller
{
    // Show data-management view with existing settings
    public function index()
    {
        $setting = SimulationSetting::latest()->first();
        return view('pages.admin.data-management', compact('setting'));
    }

    // Save simulation settings (called when clicking "Configure Simulations")
    public function store(Request $request)
    {
        $request->validate([
            'frequency' => 'required|string',
            'baseline' => 'required|integer|min:0',
            'variation' => 'required|in:random,increasing,fluctuating',
        ]);

        SimulationSetting::create([
            'frequency' => $request->frequency,
            'baseline' => $request->baseline,
            'variation' => $request->variation,
            'is_running' => false // Default as stopped
        ]);

        return response()->json(['message' => 'Simulation settings saved successfully.']);
    }

    // Toggle simulation ON/OFF from toggle switch
    public function toggleStatus()
    {
        $setting = SimulationSetting::latest()->first();

        if ($setting) {
            $setting->is_running = !$setting->is_running;
            $setting->save();

            return response()->json([
                'status' => $setting->is_running ? 'Running' : 'Stopped',
                'is_running' => $setting->is_running
            ]);
        }

        return response()->json(['error' => 'No settings found to toggle.'], 404);
    }
}
