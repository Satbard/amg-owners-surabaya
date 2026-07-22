<?php

namespace App\Mail;

use App\Models\MediaRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Picqer\Barcode\BarcodeGeneratorPNG;

class MediaBarcodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public MediaRegistration $registration;

    public string $barcodeBase64;

    /**
     * Create a new message instance.
     */
    public function __construct(MediaRegistration $registration)
    {
        $this->registration = $registration;

        // Generate barcode image inline
        $barcodeContent = $registration->barcode_token;
        $generator = new BarcodeGeneratorPNG;
        $barcodeData = $generator->getBarcode($barcodeContent, $generator::TYPE_CODE_128, 2, 50);
        $this->barcodeBase64 = base64_encode($barcodeData);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Barcode Media Registration – '.$this->registration->media_name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.media-barcode',
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
