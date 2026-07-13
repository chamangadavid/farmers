<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('jobs:update-status')->dailyAt('18:00');
        $schedule->command('app:generate-monthly-reporty')->monthlyOn(1, '00:10');
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
    }
}