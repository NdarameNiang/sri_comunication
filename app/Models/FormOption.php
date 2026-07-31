<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormOption extends Model
{
    protected $fillable = ['group', 'label', 'value', 'sort_order', 'is_active', 'is_other'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'sort_order' => 'integer', 'is_other' => 'boolean'];
    }

    public static function forGroup(string $group): \Illuminate\Support\Collection
    {
        return static::where('group', $group)->where('is_active', true)->orderBy('sort_order')->get();
    }

    /**
     * value => label map for a group, sourced from active AND inactive rows so
     * historically-submitted values still resolve to a label after an option is deactivated.
     */
    public static function labelsForGroup(string $group): array
    {
        return static::where('group', $group)->pluck('label', 'value')->all();
    }

    /**
     * Values within a group that are flagged as the free-text "Autre" trigger.
     */
    public static function otherValuesForGroup(string $group): array
    {
        return static::where('group', $group)->where('is_other', true)->pluck('value')->all();
    }

    public static function groups(): array
    {
        return [
            'scientific_domain'  => 'Domaines scientifiques',
            'project_type'       => 'Types de projet',
            'maturity_level'     => 'Niveaux de maturité',
            'protection_type'    => 'Types de protection',
            'valorisation_type'  => 'Types de valorisation',
            'impact_type'        => 'Types d\'impact',
            'presentation_format'=> 'Formats de présentation',
            'participant_type'   => 'Types de participant (inscription)',
            'population_category'=> 'Catégories de population (ciblage inscription)',
            'collaborator_role'  => 'Rôles de collaborateur',
            'trl_level'          => 'Niveaux TRL (soumission Approfondie)',
            'voie_valorisation'  => 'Voies de valorisation (soumission Approfondie)',
            'impact_dimension'   => 'Dimensions d\'impact (soumission Approfondie)',
            'annexe_type'        => 'Types d\'annexes (soumission Approfondie)',
        ];
    }
}
