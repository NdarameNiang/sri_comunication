<?php

namespace App\Mail;

use App\Models\EventConfig;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PorteurCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public ?EventConfig $event;

    public function __construct(
        public User   $porteur,
        public string $plainPassword,
    ) {
        $this->event = EventConfig::active();
    }

    public function envelope(): Envelope
    {
        $eventName = $this->event?->event_name ?? 'SRI 2026';

        return new Envelope(
            subject: "{$eventName} – Vos identifiants de connexion",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.porteur-credentials',
            with: ['event' => $this->event],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
