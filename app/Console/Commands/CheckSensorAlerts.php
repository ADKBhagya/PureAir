<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sensor;
use App\Models\AlertRule;
use App\Models\TriggeredAlert;

class CheckSensorAlerts extends Command
{
    protected $signature = 'check:sensor-alerts';
    protected $description = 'Scan active sensors, match against active rules, manage alerts dynamically';

    public function handle()
    {
        // 🧹 Step 1: Delete alerts related to inactive sensors
        $inactiveSensorIds = Sensor::where('status', 'inactive')->pluck('sensor_id');
        if ($inactiveSensorIds->isNotEmpty()) {
            TriggeredAlert::whereIn('sensor_id', $inactiveSensorIds)->delete();
        }

        // 🧹 Step 2: Delete old alerts older than 2 days
        TriggeredAlert::where('created_at', '<', now()->subDays(2))->delete();

        // 🛰 Step 3: Get all active sensors
        $sensors = Sensor::where('status', 'active')->get();

        // 📋 Step 4: Get all current rules
        $rules = AlertRule::all();

        $newAlertsCount = 0;

        // 🚀 Step 5: Loop sensors and rules
        foreach ($sensors as $sensor) {
            foreach ($rules as $rule) {
                if ($rule->pollutant_type === 'AQI') {
                    if ($sensor->aqi >= $rule->threshold) {
                        // Check if already triggered today
                        $exists = TriggeredAlert::where('sensor_id', $sensor->sensor_id)
                            ->where('pollutant_type', $rule->pollutant_type)
                            ->where('threshold', $rule->threshold)
                            ->whereDate('created_at', now()->toDateString())
                            ->first();

                        if (!$exists) {
                            TriggeredAlert::create([
                                'sensor_id' => $sensor->sensor_id,
                                'pollutant_type' => $rule->pollutant_type,
                                'threshold' => $rule->threshold,
                                'aqi_value' => $sensor->aqi,
                                'status' => 'unread',
                            ]);
                            $newAlertsCount++;
                        }
                    }
                }
            }
        }

        $this->info("✅ Sensor alerts checked. New alerts created: {$newAlertsCount}");
    }
}
