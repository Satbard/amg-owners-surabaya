<?php

namespace App\Mail;

use App\Models\MediaRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class MediaBarcodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public MediaRegistration $registration;

    public function __construct(MediaRegistration $registration)
    {
        $this->registration = $registration;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'ID Card Pass Access – '.$this->registration->media_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.media-barcode',
        );
    }

    public function attachments(): array
    {
        $path = Storage::disk('public')->path('images/ID CARD PASS ACCESS opsi.jpeg');

        if (! file_exists($path)) {
            return [];
        }

        return [
            Attachment::fromPath($path)
                ->as('ID-Card-'.$this->registration->media_name.'.jpeg')
                ->withMime('image/jpeg'),
        ];
    }
}
