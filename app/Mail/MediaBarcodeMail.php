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

    public string $barcodeCid;

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
            with: [
                'barcodeCid' => $this->barcodeCid,
            ],
        );
    }

    /**
     * Build the message.
     */
    public function build(): static
    {
        $barcodeContent = $this->registration->barcode_token;
        $generator = new BarcodeGeneratorPNG;
        $barcodeData = $generator->getBarcode($barcodeContent, $generator::TYPE_CODE_128, 2, 50);

        // Add white background to barcode
        $barcodeImg = imagecreatefromstring($barcodeData);
        $width = imagesx($barcodeImg);
        $height = imagesy($barcodeImg);
        $padding = 15;
        $canvasW = $width + ($padding * 2);
        $canvasH = $height + ($padding * 2);
        $canvas = imagecreatetruecolor($canvasW, $canvasH);
        $white = imagecolorallocate($canvas, 255, 255, 255);
        imagefill($canvas, 0, 0, $white);
        imagecopy($canvas, $barcodeImg, $padding, $padding, 0, 0, $width, $height);

        // Get PNG data from canvas
        ob_start();
        imagepng($canvas);
        $pngData = ob_get_clean();

        imagedestroy($barcodeImg);
        imagedestroy($canvas);

        // Embed as inline attachment
        $this->barcodeCid = $this->embedData($pngData, 'barcode.png', 'image/png');

        return $this;
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
