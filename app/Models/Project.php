<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'assignment_id', 'porteur_id', 'structure_id',
        'responsable_nom', 'contact_email', 'email_professionnel', 'contact_phone',
        'scientific_domain', 'project_types',
        'summary', 'problematic', 'solution',
        'results', 'maturity_level',
        'protection_types', 'protection_autres',
        'valorisation_types', 'valorisation_autres',
        'impact_types',
        'presentation_formats', 'presentation_autres',
        'logistic_needs',
        'status', 'selected', 'selected_at', 'selected_by', 'email_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'project_types'       => 'array',
            'protection_types'    => 'array',
            'valorisation_types'  => 'array',
            'impact_types'        => 'array',
            'presentation_formats'=> 'array',
            'selected'            => 'boolean',
            'selected_at'         => 'datetime',
            'email_sent_at'       => 'datetime',
        ];
    }

    public function collaborators()
    {
        return $this->hasMany(ProjectCollaborator::class);
    }

    public function assignment()
    {
        return $this->belongsTo(ProjectAssignment::class);
    }

    public function porteur()
    {
        return $this->belongsTo(User::class, 'porteur_id');
    }

    public function structure()
    {
        return $this->belongsTo(Structure::class);
    }

    public function selectedBy()
    {
        return $this->belongsTo(User::class, 'selected_by');
    }

    public function isSubmitted(): bool { return $this->status === 'submitted'; }
    public function isDraft(): bool     { return $this->status === 'draft'; }
    public function isSelected(): bool  { return (bool) $this->selected; }

    public static function projectTypeLabels(): array
    {
        return [
            'recherche'         => 'Recherche',
            'innovation'        => 'Innovation',
            'prototype'         => 'Prototype',
            'solution_appliquee'=> 'Solution appliquée',
        ];
    }

    public static function maturityLabels(): array
    {
        return [
            'prototype' => 'Prototype',
            'teste'     => 'Testé',
            'deploye'   => 'Déployé',
        ];
    }

    public static function protectionLabels(): array
    {
        return [
            'brevet'                    => 'Brevet',
            'semi_conducteur'           => 'Semi-conducteur',
            'certificat_vegetale'       => 'Certificat d\'obtention végétale',
            'dessins_modeles'           => 'Dessins et modèles industriels',
            'base_donnees'              => 'Base de données',
            'droit_auteur'              => 'Droit d\'auteur',
            'droit_voisins'             => 'Droits voisins',
            'secret'                    => 'Secret',
            'autres'                    => 'Autres',
        ];
    }

    public static function valorisationLabels(): array
    {
        return [
            'publication'               => 'Publication',
            'startup'                   => 'Start-up',
            'transfert_technologique'   => 'Transfert de technologie',
            'transfert_savoir_faire'    => 'Transfert de savoir-faire',
            'contrats_exploitation'     => 'Contrats d\'exploitation',
            'cooperation_scientifique'  => 'Coopération scientifique',
            'sociale'                   => 'Valorisation sociale',
            'economique'                => 'Valorisation économique',
            'professionnelle'           => 'Valorisation professionnelle',
        ];
    }

    public static function impactLabels(): array
    {
        return [
            'scientifique'   => 'Scientifique',
            'economique'     => 'Économique',
            'social'         => 'Social',
            'environnemental'=> 'Environnemental',
            'culturel'       => 'Culturel',
        ];
    }

    public static function presentationLabels(): array
    {
        return [
            'poster'        => 'Poster',
            'demonstration' => 'Démonstration',
            'pitch'         => 'Pitch',
            'stand'         => 'Stand',
            'autres'        => 'Autres',
        ];
    }
}
