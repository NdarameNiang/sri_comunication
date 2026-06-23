<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'email_personnel', 'phone', 'password',
        'role', 'is_active', 'structure_id',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    public function structure()
    {
        return $this->belongsTo(Structure::class);
    }

    public function structures()
    {
        return $this->belongsToMany(Structure::class, 'user_structures')->withTimestamps();
    }

    public function projectAssignments()
    {
        return $this->hasMany(ProjectAssignment::class, 'porteur_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'porteur_id');
    }

    public function isSuperAdmin(): bool        { return $this->role === 'superadmin'; }
    public function isDirectionRecherche(): bool { return $this->role === 'direction_recherche'; }
    public function isPointFocal(): bool         { return $this->role === 'point_focal'; }
    public function isPorteurProjet(): bool      { return $this->role === 'porteur_projet'; }
    public function isComiteScientifique(): bool  { return $this->role === 'comite_scientifique'; }

    public function hasRole(string|array $roles): bool
    {
        return in_array($this->role, (array) $roles);
    }

    public function isSecretaire(): bool { return $this->role === 'secretaire'; }

    public function getDashboardRoute(): string
    {
        return match($this->role) {
            'superadmin'          => 'superadmin.dashboard',
            'direction_recherche' => 'direction.dashboard',
            'porteur_projet'      => 'porteur.dashboard',
            'point_focal'         => 'point-focal.dashboard',
            'comite_scientifique' => 'comite.dashboard',
            'secretaire'          => 'secretaire.dashboard',
            default               => 'login',
        };
    }

    public static function roleLabel(string $role): string
    {
        return match($role) {
            'superadmin'          => 'Super Administrateur',
            'direction_recherche' => 'Organisateur',
            'point_focal'         => 'Observateur',
            'porteur_projet'      => 'Porteur de Projet',
            'comite_scientifique' => 'Comité Scientifique',
            'secretaire'          => 'Secrétaire',
            default               => ucfirst($role),
        };
    }
}
