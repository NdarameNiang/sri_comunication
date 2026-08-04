<?php

namespace App\Services;

use App\Models\Personnel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PersonnelSyncService
{
    /**
     * Synchronise l'intégralité du personnel PER/PATS depuis l'API UCAD vers la table
     * locale `personnels`. Contrairement à StudentCenter, cette API ne pagine pas : elle
     * renvoie un unique tableau JSON brut à la racine (~3300 lignes, ~27s de réponse côté
     * testent.ucad.sn), sans clé `data`/`last_page`. Un seul appel HTTP, donc, avec un
     * timeout large pour laisser le temps à cette réponse lente d'arriver entièrement.
     *
     * Champs bruts observés (différents des noms internes) : classification (PER/PATS),
     * emailUcad, emailPersonnel — mappés ci-dessous vers les colonnes locales.
     *
     * @param  callable(array{page:int,last_page:?int,synced:int})|null  $onProgress  Appelé après chaque lot traité.
     * @return array{pages: int, personnel: int, errors: int}
     */
    public function syncAll(int $perPage = 500, ?int $maxPages = null, ?callable $onProgress = null): array
    {
        $url   = config('services.personnel.url');
        $token = config('services.personnel.token');

        if (!$url || !$token) {
            throw new \RuntimeException('PERSONNEL_API_URL / PERSONNEL_API_TOKEN non configurés.');
        }

        try {
            $response = Http::withToken($token)
                ->timeout(120)
                ->get($url);
        } catch (\Throwable $e) {
            Log::error('Personnel sync: échec réseau', ['message' => $e->getMessage()]);
            return ['pages' => 0, 'personnel' => 0, 'errors' => 1];
        }

        if ($response->status() === 401) {
            throw new \RuntimeException('Personnel API : token invalide ou manquant (401).');
        }
        if (!$response->successful()) {
            Log::error('Personnel sync: réponse HTTP en erreur', ['status' => $response->status()]);
            return ['pages' => 0, 'personnel' => 0, 'errors' => 1];
        }

        $rows = $response->json() ?? [];
        if (!is_array($rows) || (isset($rows['data']) && is_array($rows['data']))) {
            // Au cas où l'API évoluerait un jour vers un format paginé standard {data: [...]}.
            $rows = $rows['data'] ?? [];
        }

        $total = count($rows);
        $totalPersonnel = 0;
        $batchSize = 100;

        foreach (array_chunk($rows, $batchSize) as $i => $batch) {
            foreach ($batch as $p) {
                if (empty($p['matricule'])) {
                    continue;
                }
                Personnel::updateOrCreate(
                    ['matricule' => $p['matricule']],
                    [
                        'nom'             => $p['nom'] ?? '',
                        'prenom'          => $p['prenom'] ?? '',
                        'categorie'       => isset($p['classification']) ? strtolower($p['classification']) : null,
                        'structure'       => $p['structure'] ?? null,
                        'email_ucad'      => trim($p['emailUcad'] ?? '') ?: null,
                        'email_personnel' => trim($p['emailPersonnel'] ?? '') ?: null,
                        'telephone'       => $p['telephone'] ?? null,
                        'password_hash'   => $p['password'] ?? null,
                        'synced_at'       => now(),
                    ]
                );
                $totalPersonnel++;
            }

            if ($onProgress) {
                $processed = ($i + 1) * $batchSize;
                $onProgress([
                    'page'      => min($processed, $total),
                    'last_page' => $total,
                    'synced'    => $totalPersonnel,
                ]);
            }
        }

        return [
            'pages'     => 1,
            'personnel' => $totalPersonnel,
            'errors'    => 0,
        ];
    }
}
