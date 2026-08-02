<?php

namespace App\Mail;

use App\Models\EventConfig;
use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProjectSelected extends Mailable
{
    use Queueable, SerializesModels;

    public Project $project;
    public ?EventConfig $event;

    public function __construct(Project $project)
    {
        $this->project = $project;
        $this->event = EventConfig::active();
    }

    public function envelope(): Envelope
    {
        $eventName = $this->event?->event_name ?? 'SRI 2026';

        return new Envelope(
            subject: "Félicitations – Votre projet a été sélectionné pour la {$eventName}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.project-selected',
            with: ['event' => $this->event],
        );
    }
}
