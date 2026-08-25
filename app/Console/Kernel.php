<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // App timezone stays UTC (see config/app.php); pinning the schedule
        // itself to Asia/Manila keeps this at 10:00 PM PHT regardless.
        $schedule->command('sync:employees')
            ->timezone('Asia/Manila')
            ->dailyAt('22:00')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/sync-employees.log'));
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
