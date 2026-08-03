<?php

namespace App\Console\Commands;

use App\Services\PersonnelSyncService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class SyncPersonnel extends Command
{
    public const PROGRESS_CACHE_KEY = 'sync_progress:personnel';

    protected $signature = 'personnel:sync {--max-pages= : Limiter le nombre de pages (tests)}';

    protected $description = 'Synchronise le personnel PER/PATS depuis l\'API UCAD vers la base locale';

    public function handle(PersonnelSyncService $service): int
    {
        $this->info('Synchronisation Personnel en cours…');

        $maxPages = $this->option('max-pages') ? (int) $this->option('max-pages') : null;

        Cache::put(self::PROGRESS_CACHE_KEY, [
            'status' => 'running', 'page' => 0, 'last_page' => null, 'synced' => 0,
        ], now()->addHours(2));

        try {
            $stats = $service->syncAll(maxPages: $maxPages, onProgress: function (array $progress) {
                Cache::put(self::PROGRESS_CACHE_KEY, [
                    'status'    => 'running',
                    'page'      => $progress['page'],
                    'last_page' => $progress['last_page'],
                    'synced'    => $progress['synced'],
                ], now()->addHours(2));
            });
        } catch (\Throwable $e) {
            Cache::put(self::PROGRESS_CACHE_KEY, [
                'status' => 'failed', 'message' => $e->getMessage(),
            ], now()->addHours(2));
            $this->error('Échec : ' . $e->getMessage());
            return self::FAILURE;
        }

        Cache::put(self::PROGRESS_CACHE_KEY, [
            'status' => 'done', 'stats' => $stats,
        ], now()->addHours(2));

        $this->info("Terminé : {$stats['personnel']} personnel synchronisé sur {$stats['pages']} page(s), {$stats['errors']} erreur(s).");

        return $stats['errors'] > 0 ? self::FAILURE : self::SUCCESS;
    }
}
