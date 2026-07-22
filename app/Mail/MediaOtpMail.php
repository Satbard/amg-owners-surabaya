<?php

namespace App\Mail;

use App\Models\MediaRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MediaOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public MediaRegistration $registration;

    public string $otp;

    /**
     * Create a new message instance.
     */
    public function __construct(MediaRegistration $registration, string $otp)
    {
        $this->registration = $registration;
        $this->otp = $otp;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kode Verifikasi Login Media – AMG Owners Surabaya',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.media-otp',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
