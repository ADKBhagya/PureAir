<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AlertRule;
use App\Models\TriggeredAlert;
use Illuminate\Support\Facades\Artisan;

class AlertRuleController extends Controller
{
    /**
     * Display the Alert Configuration page with all rules and triggered alerts.
     */
    public function index()
    {
        $alertRules = AlertRule::all();
        $triggeredAlerts = TriggeredAlert::latest()->get();

        return view('pages.admin.alerts', compact('alertRules', 'triggeredAlerts'));
    }

    /**
     * Store a newly created alert rule and recheck all sensors immediately.
     */
   // After rule created
public function store(Request $request)
{
    $validated = $request->validate([
        'pollutant_type' => 'required|string',
        'threshold' => 'required|integer',
        'check_frequency' => 'required|string',
        'alert_type' => 'required|string',
    ]);

    TriggeredAlert::truncate(); // 🧹 Delete old alerts
    AlertRule::create($validated);

    // 🚀 Immediately check all sensors after rule added
    \Artisan::call('check:sensor-alerts');

    return response()->json(['message' => 'Rule added and alerts checked'], 200);
}

// After rule updated
public function update(Request $request, $id)
{
    $validated = $request->validate([
        'pollutant_type' => 'required|string',
        'threshold' => 'required|integer',
        'check_frequency' => 'required|string',
        'alert_type' => 'required|string',
    ]);

    TriggeredAlert::truncate(); // 🧹 Delete old alerts
    $rule = AlertRule::findOrFail($id);
    $rule->update($validated);

    // 🚀 Immediately check all sensors after rule updated
    \Artisan::call('check:sensor-alerts');

    return response()->json(['message' => 'Rule updated and alerts checked'], 200);
}


    /**
     * Delete a specific alert rule.
     */
    public function destroy($id)
{
    $rule = AlertRule::findOrFail($id);

    // 🧹 Step 1: Delete all triggered alerts related to this rule
    \App\Models\TriggeredAlert::where('pollutant_type', $rule->pollutant_type)
        ->where('threshold', $rule->threshold)
        ->delete();

    // 🗑 Step 2: Now delete the rule itself
    $rule->delete();

    // 🚀 Step 3: (Optional but recommended) Re-run sensor alert checking to refresh new alerts
    \Artisan::call('check:sensor-alerts');

    return response()->json(['message' => 'Rule and related alerts deleted successfully'], 200);
}


    /**
     * Show details of a specific alert rule (for editing).
     */
    public function show($id)
    {
        $rule = AlertRule::findOrFail($id);
        return response()->json($rule);
    }
}
