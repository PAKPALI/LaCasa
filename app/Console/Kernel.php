<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Register scheduled tasks.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Ta tâche toutes les minutes
        $schedule->command('certification:check')->everyMinute();

        // Scheduler pour le rapport hebdomadaire
        $schedule->job(new \App\Jobs\SendWeeklyUserStats)->sundays()->at('23:59');

        // Rapport mensuel : dernier jour du mois, à 23h59
        $schedule->job(new \App\Jobs\SendMonthlyUserStats)
        ->monthlyOn(date('t'), '23:59');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
    }
}
