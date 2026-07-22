<?php

namespace App\Services;

use App\Models\MediaRegistration;
use App\Models\Registration;

class BarcodeService
{
    /**
     * Generate a cryptographically secure random token for barcode.
     *
     * The token is 8 uppercase alphanumeric characters, short enough for
     * easy camera scanning while remaining unpredictable.
     * Keyspace: 36^8 = 2.8 trillion possibilities.
     */
    public static function generateToken(): string
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $token = '';

        for ($i = 0; $i < 8; $i++) {
            $token .= $chars[random_int(0, strlen($chars) - 1)];
        }

        return $token;
    }

    /**
     * Find a member by their barcode token.
     * Returns null if not found.
     */
    public static function findByToken(string $token): ?Registration
    {
        return Registration::where('barcode_token', $token)->first();
    }

    /**
     * Find a media registration by its barcode token.
     * Returns null if not found.
     */
    public static function findMediaByToken(string $token): ?MediaRegistration
    {
        return MediaRegistration::where('barcode_token', $token)->first();
    }
}
