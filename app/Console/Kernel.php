<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\SimulateSensorData;
use App\Console\Commands\SimulateAQI;
use App\Console\Commands\CheckSensorAlerts; // ✅ Add this new command!

class Kernel extends ConsoleKernel
{
    /**
     * Register custom Artisan commands.
     */
    protected $commands = [
        SimulateSensorData::class,
        SimulateAQI::class,
        CheckSensorAlerts::class, // ✅ Register the CheckSensorAlerts command
    ];

    /**
     * Schedule tasks to run at defined intervals.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('simulate:sensor-data')->everyMinute();
        $schedule->command('simulate:aqi')->everyMinute();
        $schedule->command('simulate:sensor-data')->everyMinute();
    }

    /**
     * Load additional command routes.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
