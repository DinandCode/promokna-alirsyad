<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ParticipantConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ?object $participant = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject:  config('app.name') . ' | Konfirmasi Pengambilan Ridepack',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.participant_confirmation',
            with: [
                'participant' => $this->participant,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
