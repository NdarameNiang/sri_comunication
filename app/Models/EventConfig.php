<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventConfig extends Model
{
    protected $fillable = [
        'event_name', 'event_slug', 'event_description', 'organizer',
        'event_start_date', 'event_end_date',
        'submission_open_at', 'submission_close_at', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'event_start_date'    => 'date',
            'event_end_date'      => 'date',
            'submission_open_at'  => 'datetime',
            'submission_close_at' => 'datetime',
            'is_active'           => 'boolean',
        ];
    }

    public function registrations() { return $this->hasMany(Registration::class); }
    public function questionnaires() { return $this->hasMany(Questionnaire::class); }

    public static function active(): ?self
    {
        return static::where('is_active', true)->first();
    }

    public function isSubmissionOpen(): bool
    {
        $now = now();
        if ($this->submission_open_at  && $now->lt($this->submission_open_at))  return false;
        if ($this->submission_close_at && $now->gt($this->submission_close_at)) return false;
        return true;
    }

    public function submissionStatus(): string
    {
        $now = now();
        if ($this->submission_open_at  && $now->lt($this->submission_open_at))  return 'not_open';
        if ($this->submission_close_at && $now->gt($this->submission_close_at)) return 'closed';
        return 'open';
    }
}
