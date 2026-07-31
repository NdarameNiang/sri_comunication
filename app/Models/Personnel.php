<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Table de vérification du personnel (PER/PATS) par matricule, sur le même principe que
 * Student/StudentCenter. En attendant l'arrivée de l'API réelle du personnel, cette table
 * est alimentée par PersonnelSeeder avec des données de test — le jour où l'API est
 * disponible, un PersonnelSyncService (calqué sur StudentSyncService) alimentera cette même
 * table sans qu'aucun code appelant n'ait à changer.
 */
class Personnel extends Model
{
    protected $fillable = [
        'matricule', 'nom', 'prenom', 'categorie', 'structure', 'synced_at',
    ];

    protected function casts(): array
    {
        return ['synced_at' => 'datetime'];
    }

    public function fullName(): string
    {
        return trim($this->prenom . ' ' . $this->nom);
    }

    public function populationCategoryValue(): ?string
    {
        return $this->categorie;
    }
}
