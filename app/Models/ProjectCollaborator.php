<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectCollaborator extends Model
{
    protected $fillable = [
        'project_id', 'nom', 'prenom', 'email',
        'telephone', 'institution', 'role_collaborateur',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function fullName(): string
    {
        return trim($this->prenom . ' ' . $this->nom);
    }
}
