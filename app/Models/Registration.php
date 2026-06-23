<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = [
        'event_config_id', 'nom', 'prenom', 'email', 'telephone',
        'institution', 'fonction', 'type_participant', 'qr_code', 'presence_confirmee',
    ];

    protected function casts(): array
    {
        return ['presence_confirmee' => 'boolean'];
    }

    public function event() { return $this->belongsTo(EventConfig::class, 'event_config_id'); }

    public function fullName(): string { return trim($this->prenom . ' ' . $this->nom); }
}
