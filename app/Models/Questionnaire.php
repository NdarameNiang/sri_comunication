<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    protected $fillable = [
        'event_config_id', 'nom', 'prenom', 'email', 'institution',
        'note_organisation', 'note_contenu', 'note_logistique', 'note_globale',
        'points_positifs', 'points_amelioration', 'suggestions', 'recommanderait',
    ];

    protected function casts(): array
    {
        return ['recommanderait' => 'boolean'];
    }

    public function event() { return $this->belongsTo(EventConfig::class, 'event_config_id'); }
}
