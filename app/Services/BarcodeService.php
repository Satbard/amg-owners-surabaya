<?php

namespace App\Services;

use App\Models\Registration;

class BarcodeService
{
    /**
     * Generate a cryptographically secure random alphanumeric token.
     *
     * The token is 16 characters long and used as the barcode content.
     * Unlike the raw member_number (AMG00001), this token is unpredictable
     * and cannot be guessed or forged by members.
     */
    public static function generateToken(): string
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $token = '';

        for ($i = 0; $i < 16; $i++) {
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
}
