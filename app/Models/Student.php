<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'numero_carte', 'cin', 'nom', 'prenom',
        'annee', 'cycle', 'formation', 'structure', 'synced_at',
    ];

    protected function casts(): array
    {
        return ['synced_at' => 'datetime'];
    }

    public function fullName(): string
    {
        return trim($this->prenom . ' ' . $this->nom);
    }

    /**
     * Fait correspondre le libellé de cycle StudentCenter à une valeur du groupe
     * FormOption "population_category" (voir FormOptionSeeder).
     */
    public function populationCategoryValue(): ?string
    {
        $cycle = $this->cycle ?? '';

        return match (true) {
            str_contains($cycle, 'Licence')   => 'etudiant_licence',
            str_contains($cycle, 'Deuxieme')  => 'etudiant_master',
            str_contains($cycle, 'Troisieme') => 'etudiant_doctorat',
            default => null,
        };
    }
}
