<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentBlock extends Model
{
    protected $fillable = [
        'event_config_id', 'key', 'title', 'type', 'content', 'content_json', 'sort_order', 'is_active',
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
     * Catalogue fini des champs "structurels" — position et rôle fixés par le design
     * de la page (badge du hero, paragraphe d'intro de secours, pied de page). Distinct
     * des sections libres ci-dessous, qui sont un vrai mini-CMS piloté entièrement par
     * l'admin (titre, contenu, ordre, existence) et propre à chaque événement.
     */
    public static function keys(): array
    {
        return [
            'landing.badge_text' => ['label' => 'Badge "Appel à contribution"', 'type' => 'text'],
            'landing.intro'      => ['label' => 'Paragraphe d\'introduction (si aucune description d\'événement)', 'type' => 'richtext'],
            'landing.footer'     => ['label' => 'Texte du pied de page', 'type' => 'text'],
        ];
    }

    /**
     * Résout un champ structurel (clé fixe) : la version spécifique à l'événement gagne,
     * sinon le bloc par défaut global (event_config_id NULL), sinon null (la vue retombe
     * sur son contenu codé en dur, comme event_description aujourd'hui).
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

    /**
     * Sections libres (type CMS) d'un événement, dans l'ordre choisi par l'admin.
     * Contrairement aux champs structurels, elles n'ont pas de clé fixe : titre,
     * contenu, position et existence sont 100% pilotés depuis l'admin — deux
     * événements peuvent avoir des sections complètement différentes.
     */
    public static function sectionsFor(EventConfig $event, bool $onlyActive = true): \Illuminate\Support\Collection
    {
        return static::where('event_config_id', $event->id)
            ->whereNull('key')
            ->when($onlyActive, fn ($q) => $q->where('is_active', true))
            ->orderBy('sort_order')
            ->get();
    }
}
