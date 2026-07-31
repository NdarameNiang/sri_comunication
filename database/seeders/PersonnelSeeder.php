<?php

namespace Database\Seeders;

use App\Models\Personnel;
use Illuminate\Database\Seeder;

/**
 * Données de test en attendant l'API réelle du personnel UCAD (PER/PATS). Non destructif :
 * upsert par matricule, comme FormOptionSeeder — rejouable sans écraser d'éventuelles
 * données déjà synchronisées plus tard par un vrai PersonnelSyncService.
 */
class PersonnelSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['matricule' => 'PER-0001', 'nom' => 'Ndiaye',  'prenom' => 'Moussa',   'categorie' => 'per',  'structure' => 'FST'],
            ['matricule' => 'PER-0002', 'nom' => 'Fall',    'prenom' => 'Aminata',  'categorie' => 'per',  'structure' => 'FASEG'],
            ['matricule' => 'PER-0003', 'nom' => 'Sarr',    'prenom' => 'Ibrahima', 'categorie' => 'per',  'structure' => 'FMPO'],
            ['matricule' => 'PATS-0001','nom' => 'Diallo',  'prenom' => 'Fatou',    'categorie' => 'pats', 'structure' => 'Rectorat'],
            ['matricule' => 'PATS-0002','nom' => 'Ba',      'prenom' => 'Cheikh',   'categorie' => 'pats', 'structure' => 'DAGE'],
            ['matricule' => 'PATS-0003','nom' => 'Gueye',   'prenom' => 'Mariam',   'categorie' => 'pats', 'structure' => 'FLSH'],
        ];

        foreach ($rows as $row) {
            $row['synced_at'] = now();
            Personnel::updateOrCreate(['matricule' => $row['matricule']], $row);
        }
    }
}
