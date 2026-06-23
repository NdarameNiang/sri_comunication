<?php

namespace App\Mail;

use App\Models\Project;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubmissionConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User    $porteur,
        public Project $project,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmation de soumission – ' . $this->project->assignment?->project_title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.submission-confirmation',
        );
    }

    public function attachments(): array { return []; }
}
