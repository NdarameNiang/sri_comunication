<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'assignment_id', 'porteur_id', 'structure_id', 'submission_template',
        'responsable_nom', 'contact_email', 'email_professionnel', 'contact_phone',
        'scientific_domain', 'scientific_domain_autre', 'project_types', 'project_types_autres',
        'summary', 'problematic', 'solution',
        'results', 'maturity_level', 'maturity_level_autre',
        'protection_types', 'protection_autres',
        'valorisation_types', 'valorisation_autres',
        'impact_types', 'impact_types_autres',
        'presentation_formats', 'presentation_autres',
        'logistic_needs',
        'status', 'selected', 'selected_at', 'selected_by', 'email_sent_at',
        'average_score', 'rank_position', 'evaluation_status',
        'evaluation_finalized_at', 'evaluation_decided_by', 'evaluation_decided_at',
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
            'average_score'       => 'float',
            'evaluation_finalized_at' => 'datetime',
            'evaluation_decided_at'   => 'datetime',
        ];
    }

    public function scores()
    {
        return $this->hasMany(ProjectScore::class);
    }

    public function isEvaluationTiedPending(): bool
    {
        return $this->evaluation_status === 'tied_pending';
    }

    public function collaborators()
    {
        return $this->hasMany(ProjectCollaborator::class)->where('category', 'collaborateur');
    }

    public function coPorteurs()
    {
        return $this->hasMany(ProjectCollaborator::class)->where('category', 'co_porteur');
    }

    public function approfondiDetails()
    {
        return $this->hasOne(ProjectApprofondiDetail::class);
    }

    public function isApprofondi(): bool
    {
        return $this->submission_template === 'approfondi';
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

    /**
     * These delegate to FormOption::labelsForGroup() so every read view (show pages, PDF
     * recap, selection emails) sources labels from the same place as the submission form,
     * instead of a separate hardcoded copy that can drift from the live FormOption data.
     * A legacy fallback map is merged in (FormOption wins on key collision) so historically
     * submitted values still render a label even if the corresponding FormOption row is
     * missing/renamed in a given environment.
     */
    public static function projectTypeLabels(): array
    {
        return FormOption::labelsForGroup('project_type') + [
            'recherche'         => 'Recherche',
            'innovation'        => 'Innovation',
            'prototype'         => 'Prototype',
            'solution_appliquee'=> 'Solution appliquée',
        ];
    }

    public static function maturityLabels(): array
    {
        return FormOption::labelsForGroup('maturity_level') + [
            'prototype' => 'Prototype',
            'teste'     => 'Testé',
            'deploye'   => 'Déployé',
        ];
    }

    public static function protectionLabels(): array
    {
        return FormOption::labelsForGroup('protection_type') + [
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
        return FormOption::labelsForGroup('valorisation_type') + [
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
        return FormOption::labelsForGroup('impact_type') + [
            'scientifique'   => 'Scientifique',
            'economique'     => 'Économique',
            'social'         => 'Social',
            'environnemental'=> 'Environnemental',
            'culturel'       => 'Culturel',
        ];
    }

    public static function presentationLabels(): array
    {
        return FormOption::labelsForGroup('presentation_format') + [
            'poster'        => 'Poster',
            'demonstration' => 'Démonstration',
            'pitch'         => 'Pitch',
            'stand'         => 'Stand',
            'autres'        => 'Autres',
        ];
    }

    // ── Labels spécifiques à la soumission Approfondie ──────────────────────
    public static function trlLabels(): array { return FormOption::labelsForGroup('trl_level'); }
    public static function voieValorisationLabels(): array { return FormOption::labelsForGroup('voie_valorisation'); }
    public static function impactDimensionLabels(): array { return FormOption::labelsForGroup('impact_dimension'); }
    public static function annexeTypeLabels(): array { return FormOption::labelsForGroup('annexe_type'); }
}
