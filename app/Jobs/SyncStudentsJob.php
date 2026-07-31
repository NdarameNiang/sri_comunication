<?php

namespace App\Jobs;

use App\Services\StudentSyncService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SyncStudentsJob implements ShouldQueue
{
    use Queueable;

    public int $timeout = 3600;
    public int $tries = 1;

    public function handle(StudentSyncService $service): void
    {
        try {
            $stats = $service->syncAll();
            Log::info('StudentCenter sync (job) terminée', $stats);
        } catch (\Throwable $e) {
            Log::error('StudentCenter sync (job) échouée', ['message' => $e->getMessage()]);
        }
    }
}
