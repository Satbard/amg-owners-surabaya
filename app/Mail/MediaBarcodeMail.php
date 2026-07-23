<?php

namespace App\Mail;

use App\Models\MediaRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Picqer\Barcode\BarcodeGeneratorPNG;

class MediaBarcodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public MediaRegistration $registration;

    /**
     * Create a new message instance.
     */
    public function __construct(MediaRegistration $registration)
    {
        $this->registration = $registration;
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
     */
    public function attachments(): array
    {
        $barcodeContent = $this->registration->barcode_token;
        $generator = new BarcodeGeneratorPNG;
        $barcodeData = $generator->getBarcode($barcodeContent, $generator::TYPE_CODE_128, 2, 50);

        // Add white background using GD
        $img = imagecreatefromstring($barcodeData);
        $w = imagesx($img);
        $h = imagesy($img);
        $pad = 15;
        $canvas = imagecreatetruecolor($w + ($pad * 2), $h + ($pad * 2));
        imagefill($canvas, 0, 0, imagecolorallocate($canvas, 255, 255, 255));
        imagecopy($canvas, $img, $pad, $pad, 0, 0, $w, $h);

        ob_start();
        imagepng($canvas);
        $pngData = ob_get_clean();
        imagedestroy($img);
        imagedestroy($canvas);

        return [
            Attachment::fromData(
                fn () => $pngData,
                'barcode-media-'.$this->registration->id.'.png'
            )->withMime('image/png'),
        ];
    }
}
