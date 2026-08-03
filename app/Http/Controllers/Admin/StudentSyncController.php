<?php

namespace App\Http\Controllers\Admin;

use App\Console\Commands\SyncPersonnel;
use App\Console\Commands\SyncStudents;
use App\Http\Controllers\Controller;
use App\Models\Personnel;
use App\Models\Student;
use Illuminate\Support\Facades\Cache;

class StudentSyncController extends Controller
{
    public function index()
    {
        $studentStats = [
            'total'     => Student::count(),
            'last_sync' => Student::max('synced_at'),
        ];

        $personnelStats = [
            'total'     => Personnel::count(),
            'last_sync' => Personnel::max('synced_at'),
        ];

        return view('admin.students.index', compact('studentStats', 'personnelStats'));
    }

    public function sync()
    {
        $this->runDetached('students:sync');

        return back()->with('success', 'Synchronisation StudentCenter lancée — suivez la progression ci-dessous.');
    }

    public function syncPersonnel()
    {
        $this->runDetached('personnel:sync');

        return back()->with('success', 'Synchronisation Personnel (PER/PATS) lancée — suivez la progression ci-dessous.');
    }

    /**
     * Lance une commande artisan en processus détaché plutôt que via la file d'attente
     * database : ce serveur Laragon n'a pas de worker (php artisan queue:work) actif en
     * permanence, donc les jobs mis en file n'étaient jamais traités. `start /B` (Windows)
     * détache le processus de la requête HTTP courante, qui peut répondre immédiatement
     * pendant que la synchronisation tourne en arrière-plan.
     */
    private function runDetached(string $artisanCommand): void
    {
        $php = PHP_BINARY;
        $artisan = base_path('artisan');
        $command = "start /B \"\" \"{$php}\" \"{$artisan}\" {$artisanCommand}";
        pclose(popen($command, 'r'));
    }

    public function status(string $type)
    {
        $key = match ($type) {
            'students'  => SyncStudents::PROGRESS_CACHE_KEY,
            'personnel' => SyncPersonnel::PROGRESS_CACHE_KEY,
            default     => abort(404),
        };

        return response()->json(Cache::get($key, ['status' => 'idle']));
    }
}
