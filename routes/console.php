<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\DeactivateExpiredPublications;

// Exemple de commande existante (tu peux la laisser)
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// 🔔 Planification du job de désactivation automatique
Schedule::job(new DeactivateExpiredPublications())->dailyAt('00:00');
// Schedule::job(new DeactivateExpiredPublications())->everyMinute();

 // Suppression quotidienne à 01h
$schedule->job(new \App\Jobs\DeleteInactivePublications())->dailyAt('01:00');