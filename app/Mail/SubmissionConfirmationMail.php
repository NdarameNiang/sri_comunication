<?php

namespace App\Mail;

use App\Models\Project;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
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
            subject: 'Confirmation de soumission – ' . ($this->project->assignment?->title ?? 'Communication'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.submission-confirmation',
        );
    }

    public function attachments(): array
    {
        $pdf = Pdf::loadView('pdf.submission-recap', [
            'project' => $this->project,
            'porteur' => $this->porteur,
        ])
        ->setPaper('a4', 'portrait')
        ->setOptions(['defaultFont' => 'DejaVu Sans', 'isRemoteEnabled' => false]);

        $filename = 'recap-soumission-' . \Illuminate\Support\Str::slug(
            $this->project->assignment?->title ?? 'projet'
        ) . '.pdf';

        return [
            Attachment::fromData(fn () => $pdf->output(), $filename)
                ->withMime('application/pdf'),
        ];
    }
}
