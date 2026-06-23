<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormOption extends Model
{
    protected $fillable = ['group', 'label', 'value', 'sort_order', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'sort_order' => 'integer'];
    }

    public static function forGroup(string $group): \Illuminate\Support\Collection
    {
        return static::where('group', $group)->where('is_active', true)->orderBy('sort_order')->get();
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
            'collaborator_role'  => 'Rôles de collaborateur',
        ];
    }
}
