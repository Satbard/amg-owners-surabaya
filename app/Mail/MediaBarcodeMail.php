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

    /**
     * Create a new message instance.
     */
    public function __construct(MediaRegistration $registration)
    {
        $this->registration = $registration;
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

        // Embed as inline attachment and get CID
        $barcodeCid = $this->embedData($pngData, 'barcode.png', 'image/png');

        return $this->view('emails.media-barcode')
            ->subject('Barcode Media Registration – '.$this->registration->media_name)
            ->with([
                'barcodeCid' => $barcodeCid,
            ]);
    }
}
