<?php

use App\Jobs\SendWeeklyUserStats;
use App\Jobs\SendMonthlyUserStats;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Jobs\DeleteInactivePublications;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\DeactivateExpiredPublications;
use App\Jobs\WarningDeleteInactivePublications;

// Exemple de commande existante (tu peux la laisser)
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// 🔔 Planification du job de désactivation automatique
Schedule::job(new DeactivateExpiredPublications())->dailyAt('00:00');
// Schedule::job(new DeactivateExpiredPublications())->everyMinute();

// Rappel de Suppression quotidienne à 01h
Schedule::job(new WarningDeleteInactivePublications())->dailyAt('01:00');
// Schedule::job(new WarningDeleteInactivePublications())->everyMinute();

// Suppression quotidienne à 01h
Schedule::job(new DeleteInactivePublications())->dailyAt('01:00');
// Schedule::job(new DeleteInactivePublications())->everyMinute();


// Scheduler pour le rapport hebdomadaire
Schedule::job(new SendWeeklyUserStats)->sundays()->at('23:55');
// Schedule::job(new SendWeeklyUserStats)->everyMinute();

// Rapport mensuel : dernier jour du mois, à 23h59
Schedule::job(new SendMonthlyUserStats)->monthlyOn(date('t'), '23:55');
//  Schedule::job(new SendMonthlyUserStats)->everyMinute();