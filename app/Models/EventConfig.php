<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventConfig extends Model
{
    protected $fillable = [
        'event_name', 'event_slug', 'event_description', 'organizer',
        'event_start_date', 'event_end_date',
        'submission_open_at', 'submission_close_at',
        'inscription_open_at', 'inscription_close_at',
        'is_active', 'show_questionnaire', 'max_projects_per_structure', 'max_projects_per_porteur',
        'selection_quota', 'evaluation_open_at', 'evaluation_close_at',
        'allow_public_submission', 'require_identity_verification', 'enabled_profile_types',
        'logo_path', 'logo_white_path', 'hero_image_path', 'primary_color',
    ];

    protected function casts(): array
    {
        return [
            'event_start_date'     => 'date',
            'event_end_date'       => 'date',
            'submission_open_at'   => 'datetime',
            'submission_close_at'  => 'datetime',
            'inscription_open_at'  => 'datetime',
            'inscription_close_at' => 'datetime',
            'evaluation_open_at'   => 'datetime',
            'evaluation_close_at'  => 'datetime',
            'is_active'            => 'boolean',
            'show_questionnaire'   => 'boolean',
            'allow_public_submission' => 'boolean',
            'require_identity_verification' => 'boolean',
            'enabled_profile_types' => 'array',
        ];
    }

    public function registrations() { return $this->hasMany(Registration::class); }
    public function questionnaires() { return $this->hasMany(Questionnaire::class); }
    public function projectAssignments() { return $this->hasMany(ProjectAssignment::class); }

    /**
     * Branding par événement : chaque champ est nullable avec un repli sur l'apparence UCAD
     * actuelle, pour qu'un événement sans branding renseigné garde exactement le rendu
     * d'aujourd'hui. Même principe que ContentBlock::getImageUrlAttribute().
     */
    public function getLogoUrlAttribute(): string
    {
        return $this->logo_path ? \Illuminate\Support\Facades\Storage::url($this->logo_path) : asset('images/logo_ucad.png');
    }

    public function getLogoWhiteUrlAttribute(): string
    {
        return $this->logo_white_path ? \Illuminate\Support\Facades\Storage::url($this->logo_white_path) : asset('images/logo_ucad_white.png');
    }

    public function getHeroImageUrlAttribute(): string
    {
        return $this->hero_image_path ? \Illuminate\Support\Facades\Storage::url($this->hero_image_path) : asset('images/ucad_bg_1.jpg');
    }

    public function primaryColor(): string
    {
        return $this->primary_color ?: '#2563eb';
    }

    public function evaluationRubrics()
    {
        return $this->hasMany(EvaluationRubric::class);
    }

    public function rubricFor(string $submissionTemplate): ?EvaluationRubric
    {
        return $this->evaluationRubrics()->where('submission_template', $submissionTemplate)->first();
    }

    public function isEvaluationOpen(): bool
    {
        $now = now();
        if ($this->evaluation_open_at  && $now->lt($this->evaluation_open_at))  return false;
        if ($this->evaluation_close_at && $now->gt($this->evaluation_close_at)) return false;
        return true;
    }

    public function audienceCategories()
    {
        return $this->belongsToMany(FormOption::class, 'event_audience_categories');
    }

    /**
     * Un pivot vide = inscription ouverte à tous (comportement historique, rétrocompatible).
     */
    public function isAudienceRestricted(): bool
    {
        return $this->audienceCategories()->exists();
    }

    public function allowedAudienceValues(): array
    {
        return $this->audienceCategories()->pluck('value')->all();
    }

    public static function active(): ?self
    {
        return static::where('is_active', true)->first();
    }

    public function isInscriptionOpen(): bool
    {
        $now = now();
        if ($this->inscription_open_at  && $now->lt($this->inscription_open_at))  return false;
        if ($this->inscription_close_at && $now->gt($this->inscription_close_at)) return false;
        return true;
    }

    public function isSubmissionOpen(): bool
    {
        $now = now();
        if ($this->submission_open_at  && $now->lt($this->submission_open_at))  return false;
        if ($this->submission_close_at && $now->gt($this->submission_close_at)) return false;
        return true;
    }

    public function allowsPublicSubmission(): bool
    {
        return $this->allow_public_submission && $this->isSubmissionOpen();
    }

    /**
     * Contrôle si l'inscription et la soumission publique de cet événement exigent une
     * correspondance en base (StudentCenter / Personnel) avant d'accepter le dossier, ou si
     * n'importe qui peut s'inscrire/soumettre en saisie libre. Par défaut à true pour ne rien
     * changer au comportement historique (SRI 2026 reste filtré).
     */
    public function requiresIdentityVerification(): bool
    {
        return $this->require_identity_verification;
    }

    /**
     * Profils d'identification proposés sur l'inscription/le dépôt public (étudiant, personnel,
     * autre), configurés par l'admin. Aucun profil coché = formulaire libre sans sélecteur de
     * profil (comportement historique).
     */
    public function enabledProfileTypes(): array
    {
        return $this->enabled_profile_types ?? [];
    }

    public function hasProfileTypeSelection(): bool
    {
        return count($this->enabledProfileTypes()) > 0;
    }

    public function submissionStatus(): string
    {
        $now = now();
        if ($this->submission_open_at  && $now->lt($this->submission_open_at))  return 'not_open';
        if ($this->submission_close_at && $now->gt($this->submission_close_at)) return 'closed';
        return 'open';
    }
}
