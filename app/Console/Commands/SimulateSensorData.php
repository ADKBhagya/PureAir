<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sensor;
use App\Models\SimulationSetting;
use App\Models\SensorReading;

class SimulateSensorData extends Command
{
    protected $signature = 'simulate:sensor-data';
    protected $description = 'Generate simulated AQI data for each active sensor based on simulation settings';

    public function handle()
    {
        $setting = SimulationSetting::latest()->first();

        if (!$setting || !$setting->is_running) {
            $this->info('Simulation is not running. Exiting...');
            return;
        }

        $sensors = Sensor::where('status', 'active')->get();

        foreach ($sensors as $sensor) {
            $aqi = $this->generateAqi($setting->baseline, $setting->variation);
            
            SensorReading::create([
                'sensor_id' => $sensor->sensor_id,
                'aqi' => $aqi,
            ]);
        }

        $this->info('Simulated AQI data generated for all active sensors.');
    }

    private function generateAqi($baseline, $variation)
    {
        switch ($variation) {
            case 'random':
                return rand($baseline - 20, $baseline + 20);
            case 'increasing':
                return $baseline + rand(5, 25);
            case 'fluctuating':
                return $baseline + rand(-15, 15);
            default:
                return $baseline;
        }
    }
}
