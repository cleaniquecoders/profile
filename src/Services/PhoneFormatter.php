<?php

namespace CleaniqueCoders\Profile\Services;

class PhoneFormatter
{
    /**
     * Format phone number to E.164 international format (+60123456789).
     */
    public static function toE164(string $phone, string $countryCode = '60'): string
    {
        // Remove all non-numeric characters
        $cleaned = preg_replace('/[^0-9]/', '', $phone);

        // If it starts with 0, remove it (local format)
        if (str_starts_with($cleaned, '0')) {
            $cleaned = substr($cleaned, 1);
        }

        // If it doesn't start with country code, add it
        if (! str_starts_with($cleaned, $countryCode)) {
            $cleaned = $countryCode.$cleaned;
        }

        return '+'.$cleaned;
    }

    /**
     * Format phone number to national format (0123456789).
     */
    public static function toNational(string $phone, string $countryCode = '60'): string
    {
        $e164 = self::toE164($phone, $countryCode);
        $cleaned = preg_replace('/[^0-9]/', '', $e164);

        // Remove country code
        if (str_starts_with($cleaned, $countryCode)) {
            $cleaned = substr($cleaned, strlen($countryCode));
        }

        return '0'.$cleaned;
    }

    /**
     * Format phone number to international format without + symbol.
     */
    public static function toInternational(string $phone, string $countryCode = '60'): string
    {
        $e164 = self::toE164($phone, $countryCode);

        return ltrim($e164, '+');
    }

    /**
     * Format phone number with spaces for readability.
     * Example: +60 12 345 6789
     */
    public static function toReadable(string $phone, string $countryCode = '60'): string
    {
        $e164 = self::toE164($phone, $countryCode);
        $cleaned = ltrim($e164, '+');

        // Malaysian format: +60 12 345 6789
        if ($countryCode === '60' && strlen($cleaned) === 11) {
            return '+'.substr($cleaned, 0, 2).' '.substr($cleaned, 2, 2).' '.substr($cleaned, 4, 3).' '.substr($cleaned, 7);
        }

        // US format: +1 (234) 567-8901
        if ($countryCode === '1' && strlen($cleaned) === 11) {
            return '+'.substr($cleaned, 0, 1).' ('.substr($cleaned, 1, 3).') '.substr($cleaned, 4, 3).'-'.substr($cleaned, 7);
        }

        // Default: Add space after country code
        return '+'.$countryCode.' '.substr($cleaned, strlen($countryCode));
    }

    /**
     * Clean phone number (remove all formatting).
     */
    public static function clean(string $phone): string
    {
        return preg_replace('/[^0-9+]/', '', $phone);
    }

    /**
     * Validate phone number format.
     */
    public static function isValid(string $phone, ?string $countryCode = null): bool
    {
        $cleaned = preg_replace('/[^0-9]/', '', $phone);

        // Check minimum and maximum length
        if (strlen($cleaned) < 7 || strlen($cleaned) > 15) {
            return false;
        }

        // If country code specified, validate length for that country
        if ($countryCode === '60') {
            // Malaysian numbers: 9-10 digits (without country code)
            $withoutCountryCode = str_starts_with($cleaned, '60') ? substr($cleaned, 2) : ltrim($cleaned, '0');

            return strlen($withoutCountryCode) >= 9 && strlen($withoutCountryCode) <= 10;
        }

        if ($countryCode === '1') {
            // US/Canada numbers: 10 digits (without country code)
            $withoutCountryCode = str_starts_with($cleaned, '1') ? substr($cleaned, 1) : $cleaned;

            return strlen($withoutCountryCode) === 10;
        }

        return true;
    }

    /**
     * Detect country code from phone number.
     */
    public static function detectCountryCode(string $phone): ?string
    {
        $cleaned = preg_replace('/[^0-9]/', '', $phone);

        // Common country codes
        $countryCodes = [
            '1' => ['length' => 11], // US/Canada
            '44' => ['length' => 12], // UK
            '60' => ['length' => 11, 'altLength' => 12], // Malaysia
            '65' => ['length' => 10], // Singapore
            '86' => ['length' => 13], // China
            '91' => ['length' => 12], // India
        ];

        foreach ($countryCodes as $code => $config) {
            if (str_starts_with($cleaned, $code)) {
                $expectedLength = $config['length'];
                $altLength = $config['altLength'] ?? null;

                if (strlen($cleaned) === $expectedLength || ($altLength && strlen($cleaned) === $altLength)) {
                    return $code;
                }
            }
        }

        return null;
    }

    /**
     * Standardize phone number (convert to E.164 format).
     */
    public static function standardize(string $phone, ?string $countryCode = null): string
    {
        // Try to detect country code if not provided
        if (! $countryCode) {
            $detectedCode = self::detectCountryCode($phone);
            $countryCode = $detectedCode ?? '60'; // Default to Malaysia
        }

        return self::toE164($phone, $countryCode);
    }
}
