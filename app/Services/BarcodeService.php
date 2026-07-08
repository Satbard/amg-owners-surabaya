<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;

class BarcodeService
{
    /**
     * Encrypt a registration ID into a URL-safe barcode token.
     *
     * The token contains the encrypted registration ID which can only be
     * decrypted by the server holding the APP_KEY. This prevents members
     * from reading or generating valid barcodes manually.
     */
    public static function encrypt(int $registrationId): string
    {
        $encrypted = Crypt::encryptString((string) $registrationId);

        // Convert standard base64 to URL-safe base64
        // (remove + / = characters that may cause issues with barcode scanners)
        $safe = str_replace(['+', '/', '='], ['-', '_', ''], $encrypted);

        return $safe;
    }

    /**
     * Decrypt a barcode token back to a registration ID.
     *
     * Returns null if the token cannot be decrypted (invalid or tampered).
     */
    public static function decrypt(string $token): ?int
    {
        try {
            // Restore standard base64 from URL-safe format
            $original = str_replace(['-', '_'], ['+', '/'], $token);

            $decrypted = Crypt::decryptString($original);

            return (int) $decrypted;
        } catch (\Exception $e) {
            // Token is invalid, tampered, or APP_KEY has changed
            return null;
        }
    }
}
