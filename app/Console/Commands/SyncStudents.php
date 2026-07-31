<?php

namespace App\Console\Commands;

use App\Services\StudentSyncService;
use Illuminate\Console\Command;

class SyncStudents extends Command
{
    protected $signature = 'students:sync {--max-pages= : Limiter le nombre de pages (tests)}';

    protected $description = 'Synchronise les étudiants depuis l\'API StudentCenter UCAD vers la base locale';

    public function handle(StudentSyncService $service): int
    {
        $this->info('Synchronisation StudentCenter en cours…');

        $maxPages = $this->option('max-pages') ? (int) $this->option('max-pages') : null;

        try {
            $stats = $service->syncAll(maxPages: $maxPages);
        } catch (\Throwable $e) {
            $this->error('Échec : ' . $e->getMessage());
            return self::FAILURE;
        }

        $this->info("Terminé : {$stats['students']} étudiant(s) synchronisé(s) sur {$stats['pages']} page(s), {$stats['errors']} erreur(s).");

        return $stats['errors'] > 0 ? self::FAILURE : self::SUCCESS;
    }
}
