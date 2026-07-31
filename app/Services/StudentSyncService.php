<?php

namespace App\Services;

use App\Models\Student;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StudentSyncService
{
    /**
     * Synchronise l'intégralité des étudiants depuis l'API StudentCenter vers la
     * table locale `students`. Pagine jusqu'au bout (~3000 pages à 500/page pour
     * ~156k étudiants côté API) — à lancer en tâche planifiée, pas en requête web.
     *
     * @return array{pages: int, students: int, errors: int}
     */
    public function syncAll(int $perPage = 500, ?int $maxPages = null): array
    {
        $url   = config('services.studentcenter.url');
        $token = config('services.studentcenter.token');

        if (!$url || !$token) {
            throw new \RuntimeException('STUDENTCENTER_API_URL / STUDENTCENTER_API_TOKEN non configurés.');
        }

        $page = 1;
        $totalStudents = 0;
        $errors = 0;

        do {
            try {
                $response = Http::withToken($token)
                    ->timeout(30)
                    ->get($url, ['per_page' => $perPage, 'page' => $page]);
            } catch (\Throwable $e) {
                Log::error('StudentCenter sync: échec réseau', ['page' => $page, 'message' => $e->getMessage()]);
                $errors++;
                break;
            }

            if ($response->status() === 401) {
                throw new \RuntimeException('StudentCenter API : token invalide ou manquant (401).');
            }
            if (!$response->successful()) {
                Log::error('StudentCenter sync: réponse HTTP en erreur', ['page' => $page, 'status' => $response->status()]);
                $errors++;
                break;
            }

            $json = $response->json();
            $students = $json['data'] ?? [];

            foreach ($students as $s) {
                $current = $s['inscription_courante'] ?? null;
                Student::updateOrCreate(
                    ['numero_carte' => $s['numero_carte']],
                    [
                        'cin'       => $s['cin'] ?? null,
                        'nom'       => $s['nom'] ?? '',
                        'prenom'    => $s['prenom'] ?? '',
                        'annee'     => $current['annee'] ?? null,
                        'cycle'     => $current['cycle'] ?? null,
                        'formation' => $current['formation'] ?? null,
                        'structure' => $current['structure'] ?? null,
                        'synced_at' => now(),
                    ]
                );
                $totalStudents++;
            }

            $lastPage = $json['last_page'] ?? $page;
            $page++;
        } while ($page <= $lastPage && (!$maxPages || $page <= $maxPages));

        return [
            'pages'    => $page - 1,
            'students' => $totalStudents,
            'errors'   => $errors,
        ];
    }
}
