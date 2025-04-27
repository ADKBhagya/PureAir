<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sensor;
use App\Models\SensorReading;
use App\Models\SimulationSetting;

class SimulateSensorData extends Command
{
    protected $signature = 'simulate:sensor-data';
    protected $description = 'Simulate AQI sensor readings and store into database';

    public function handle()
    {
        $setting = SimulationSetting::latest()->first();

        if (!$setting || !$setting->is_running) {
            $this->info('Simulation not running.');
            return;
        }

        $baseline = $setting->baseline;
        $variation = $setting->variation;

        $sensors = Sensor::where('status', 'active')->get();

        foreach ($sensors as $sensor) {
            $aqi = $this->generateAQI($baseline, $variation);

            SensorReading::create([
                'sensor_id' => $sensor->sensor_id,
                'location' => $sensor->location,
                'status' => $sensor->status,
                'lat' => $sensor->lat,
                'lng' => $sensor->lng,
                'aqi' => $aqi,
                'created_at' => now(),
            ]);

            $sensor->update([
                'aqi' => $aqi,
                'updated_at' => now(),
            ]);
        }

        $this->info('Sensor AQI values simulated successfully.');
    }

    private function generateAQI($baseline, $variation)
    {
        switch ($variation) {
            case 'random':
                return rand(max(0, $baseline - 20), $baseline + 20);
            case 'increasing':
                return $baseline + rand(5, 25);
            case 'fluctuating':
                return $baseline + rand(-15, 15);
            default:
                return $baseline;
        }
    }
}
