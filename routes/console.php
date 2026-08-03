<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Synchronisation StudentCenter — hors heures ouvrées, la base source (~156k
// étudiants) est volumineuse et l'appel prend plusieurs dizaines de minutes.
Schedule::command('students:sync')->dailyAt('02:00')->withoutOverlapping()->onOneServer();

// Synchronisation Personnel PER/PATS — même principe, décalée pour ne pas
// tourner en même temps que students:sync.
Schedule::command('personnel:sync')->dailyAt('02:30')->withoutOverlapping()->onOneServer();
