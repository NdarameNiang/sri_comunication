<?php

namespace Database\Seeders;

use App\Models\EvaluationRubric;
use App\Models\EventConfig;
use Illuminate\Database\Seeder;

/**
 * Crée les grilles d'évaluation par défaut (standard + approfondi) pour chaque événement qui
 * n'en a pas encore, sans jamais toucher aux grilles déjà existantes ni à leurs notes.
 * `EvaluationRubric::ensureDefaults()` est idempotent (firstOrCreate par événement+template).
 */
class EvaluationRubricSeeder extends Seeder
{
    public function run(): void
    {
        EventConfig::all()->each(function (EventConfig $event) {
            EvaluationRubric::ensureDefaults($event);
        });
    }
}
