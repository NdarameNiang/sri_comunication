<?php

namespace App\Jobs;

use App\Services\PersonnelSyncService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SyncPersonnelJob implements ShouldQueue
{
    use Queueable;

    public int $timeout = 3600;
    public int $tries = 1;

    public function handle(PersonnelSyncService $service): void
    {
        try {
            $stats = $service->syncAll();
            Log::info('Personnel sync (job) terminée', $stats);
        } catch (\Throwable $e) {
            Log::error('Personnel sync (job) échouée', ['message' => $e->getMessage()]);
        }
    }
}
