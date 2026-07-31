<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationRubric extends Model
{
    protected $fillable = ['event_config_id', 'submission_template', 'name'];

    public function event()
    {
        return $this->belongsTo(EventConfig::class, 'event_config_id');
    }

    public function criteria()
    {
        return $this->hasMany(EvaluationCriterion::class, 'rubric_id')->orderBy('sort_order');
    }

    public function totalPoints(): int
    {
        return (int) $this->criteria->sum('max_points');
    }

    /**
     * Grilles par défaut (éditables ensuite par l'admin), pré-remplies pour qu'un événement
     * dispose immédiatement d'une grille exploitable pour chaque template de soumission.
     */
    public static function defaultCriteria(string $submissionTemplate): array
    {
        return match ($submissionTemplate) {
            'standard' => [
                ['label' => 'Pertinence et clarté de la problématique', 'max_points' => 15],
                ['label' => 'Qualité scientifique et rigueur de la solution proposée', 'max_points' => 20],
                ['label' => 'Caractère innovant', 'max_points' => 15],
                ['label' => 'Faisabilité et résultats attendus', 'max_points' => 15],
                ['label' => 'Adéquation avec les axes prioritaires de la SRI', 'max_points' => 10],
                ['label' => 'Impact potentiel (scientifique, économique, social)', 'max_points' => 10],
                ['label' => 'Qualité de la présentation du dossier', 'max_points' => 10],
                ['label' => 'Pertinence de l\'équipe et des collaborateurs', 'max_points' => 5],
            ],
            'approfondi' => [
                ['label' => 'Qualité scientifique et état de l\'art', 'max_points' => 15],
                ['label' => 'Caractère innovant et différenciation', 'max_points' => 15],
                ['label' => 'Robustesse méthodologique', 'max_points' => 10],
                ['label' => 'Résultats consolidés (scientifiques et techniques)', 'max_points' => 15],
                ['label' => 'Niveau de maturité technologique (TRL)', 'max_points' => 10],
                ['label' => 'Potentiel de valorisation et propriété intellectuelle', 'max_points' => 10],
                ['label' => 'Impact et pertinence pour le Sénégal et l\'Afrique', 'max_points' => 10],
                ['label' => 'Viabilité du modèle économique et des partenariats', 'max_points' => 10],
                ['label' => 'Qualité du dossier et des annexes fournies', 'max_points' => 5],
            ],
            default => [],
        };
    }

    public static function ensureDefaults(EventConfig $event): void
    {
        foreach (['standard', 'approfondi'] as $template) {
            $rubric = static::firstOrCreate(
                ['event_config_id' => $event->id, 'submission_template' => $template],
                ['name' => null]
            );

            if ($rubric->wasRecentlyCreated) {
                foreach (static::defaultCriteria($template) as $i => $criterion) {
                    $rubric->criteria()->create([
                        'label'      => $criterion['label'],
                        'max_points' => $criterion['max_points'],
                        'sort_order' => $i,
                    ]);
                }
            }
        }
    }

    /**
     * Une grille ne peut plus être modifiée structurellement (ajout/suppression de critère,
     * changement de barème) dès qu'au moins une note a été soumise dessus, pour éviter la
     * dérive entre grille et notes déjà données.
     */
    public function isLocked(): bool
    {
        $criterionIds = $this->criteria()->pluck('id');
        if ($criterionIds->isEmpty()) {
            return false;
        }

        return ProjectScoreDetail::whereIn('evaluation_criterion_id', $criterionIds)
            ->whereHas('projectScore', fn ($q) => $q->where('status', 'submitted'))
            ->exists();
    }
}
