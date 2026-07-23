<?php

namespace App\Mail;

use App\Models\MediaRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
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

        // Generate barcode and encode as base64 in constructor
        $barcodeContent = $registration->barcode_token;
        $generator = new BarcodeGeneratorPNG;
        $barcodeData = $generator->getBarcode($barcodeContent, $generator::TYPE_CODE_128, 2, 50);
        $this->barcodeBase64 = base64_encode($barcodeData);
    }

    /**
     * Build the message.
     */
    public function build(): static
    {
        return $this->view('emails.media-barcode')
            ->subject('Barcode Media Registration – '.$this->registration->media_name);
    }
}
