<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentBlock extends Model
{
    protected $fillable = [
        'event_config_id', 'key', 'type', 'content', 'content_json', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'content_json' => 'array',
            'sort_order'   => 'integer',
            'is_active'    => 'boolean',
        ];
    }

    public function event()
    {
        return $this->belongsTo(EventConfig::class, 'event_config_id');
    }

    /**
     * Catalogue fini des clés éditables — pas de texte libre, pour garder la page
     * publique structurellement stable (même principe que FormOption::groups()).
     */
    public static function keys(): array
    {
        return [
            'landing.badge_text'        => ['label' => 'Badge "Appel à contribution"', 'type' => 'text'],
            'landing.intro'             => ['label' => 'Paragraphe d\'introduction (si aucune description d\'événement)', 'type' => 'richtext'],
            'landing.objectives'        => ['label' => 'Objectifs de l\'appel', 'type' => 'list'],
            'landing.who_can_apply'     => ['label' => 'Qui peut candidater ?', 'type' => 'list'],
            'landing.expected_projects' => ['label' => 'Projets attendus', 'type' => 'list'],
            'landing.submission_modalities' => ['label' => 'Modalités de soumission', 'type' => 'richtext'],
            'landing.calendar'          => ['label' => 'Calendrier', 'type' => 'list'],
            'landing.criteria'          => ['label' => 'Critères de sélection', 'type' => 'list'],
            'landing.closing'           => ['label' => 'Paragraphe de clôture', 'type' => 'richtext'],
            'landing.footer'            => ['label' => 'Texte du pied de page', 'type' => 'text'],
        ];
    }

    /**
     * Résout un bloc : la version spécifique à l'événement gagne, sinon le bloc par
     * défaut global (event_config_id NULL), sinon null (la vue retombe sur son
     * contenu codé en dur, comme event_description aujourd'hui).
     */
    public static function resolve(string $key, ?EventConfig $event = null): ?self
    {
        if ($event) {
            $specific = static::where('key', $key)->where('event_config_id', $event->id)->where('is_active', true)->first();
            if ($specific) {
                return $specific;
            }
        }

        return static::where('key', $key)->whereNull('event_config_id')->where('is_active', true)->first();
    }
}
