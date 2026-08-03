<?php

namespace App\Services;

use App\Models\Personnel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PersonnelSyncService
{
    /**
     * Synchronise l'intégralité du personnel PER/PATS depuis l'API UCAD vers la table
     * locale `personnels`. Calqué sur StudentSyncService — pagine jusqu'au bout, à lancer
     * en tâche planifiée plutôt qu'en requête web.
     *
     * @return array{pages: int, personnel: int, errors: int}
     */
    public function syncAll(int $perPage = 500, ?int $maxPages = null): array
    {
        $url   = config('services.personnel.url');
        $token = config('services.personnel.token');

        if (!$url || !$token) {
            throw new \RuntimeException('PERSONNEL_API_URL / PERSONNEL_API_TOKEN non configurés.');
        }

        $page = 1;
        $totalPersonnel = 0;
        $errors = 0;

        do {
            try {
                $response = Http::withToken($token)
                    ->timeout(30)
                    ->get($url, ['per_page' => $perPage, 'page' => $page]);
            } catch (\Throwable $e) {
                Log::error('Personnel sync: échec réseau', ['page' => $page, 'message' => $e->getMessage()]);
                $errors++;
                break;
            }

            if ($response->status() === 401) {
                throw new \RuntimeException('Personnel API : token invalide ou manquant (401).');
            }
            if (!$response->successful()) {
                Log::error('Personnel sync: réponse HTTP en erreur', ['page' => $page, 'status' => $response->status()]);
                $errors++;
                break;
            }

            $json = $response->json();
            $rows = $json['data'] ?? [];

            foreach ($rows as $p) {
                Personnel::updateOrCreate(
                    ['matricule' => $p['matricule']],
                    [
                        'nom'             => $p['nom'] ?? '',
                        'prenom'          => $p['prenom'] ?? '',
                        'categorie'       => $p['categorie'] ?? null,
                        'structure'       => $p['structure'] ?? null,
                        'email_ucad'      => trim($p['email_ucad'] ?? '') ?: null,
                        'email_personnel' => trim($p['email_personnel'] ?? '') ?: null,
                        'telephone'       => $p['telephone'] ?? null,
                        'synced_at'       => now(),
                    ]
                );
                $totalPersonnel++;
            }

            $lastPage = $json['last_page'] ?? $page;
            $page++;
        } while ($page <= $lastPage && (!$maxPages || $page <= $maxPages));

        return [
            'pages'     => $page - 1,
            'personnel' => $totalPersonnel,
            'errors'    => $errors,
        ];
    }
}
