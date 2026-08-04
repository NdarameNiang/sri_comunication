<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'numero_carte', 'cin', 'nom', 'prenom',
        'email_ucad', 'email_personnel', 'telephone', 'sexe', 'adresse',
        'annee', 'cycle', 'formation', 'structure', 'synced_at', 'password_hash',
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
     * Email institutionnel privilégié pour pré-remplir les formulaires publics — l'API
     * StudentCenter renvoie parfois l'email personnel avec des espaces parasites en fin de
     * chaîne, d'où le trim systématique.
     */
    public function preferredEmail(): ?string
    {
        return trim($this->email_ucad ?? $this->email_personnel ?? '') ?: null;
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

    /**
     * Temporaire : StudentCenter n'expose encore aucun mot de passe dans son API — le
     * mot de passe vérifié ici est un SHA1(numero_carte) généré côté sync (voir
     * StudentSyncService), pas une donnée réelle de l'UCAD. À remplacer par un vrai
     * password_verify() bcrypt dès que l'API exposera le champ, sans changement de schéma.
     */
    public function checkPassword(string $plainPassword): bool
    {
        return $this->password_hash && hash_equals($this->password_hash, sha1($plainPassword));
    }
}
