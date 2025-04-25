<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\SimulateSensorData; // ✅ Include your command class

class Kernel extends ConsoleKernel
{
    /**
     * Register custom Artisan commands.
     */
    protected $commands = [
        SimulateSensorData::class, // ✅ Register the SimulateSensorData command
    ];

    /**
     * Schedule tasks to run at defined intervals.
     */
    protected function schedule(Schedule $schedule)
    {
        // ✅ This tells Laravel to run your simulation every 5 minutes
        $schedule->command('simulate:sensor-data')->everyFiveMinutes();
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
