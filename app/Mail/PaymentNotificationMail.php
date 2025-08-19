<?php

namespace App\Mail;

use App\Models\Participant;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $participant;
    public $payment;

    /**
     * Create a new message instance.
     */
    public function __construct(Participant $participant, Payment $payment)
    {
        $this->participant = $participant;
        $this->payment = $payment;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: config('app.name') . ' | Pembayaran Pendaftaran',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-notification',
            with: [
                'participant' => $this->participant,
                'payment' => $this->payment,
            ]
        );
    }
}
