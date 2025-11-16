<?php

namespace CleaniqueCoders\Profile\Services\AddressFormatters;

class UnitedKingdomAddressFormatter extends AbstractAddressFormatter
{
    public function getCountryCode(): string
    {
        return 'UK';
    }

    public function formatPostcode(string $postcode): string
    {
        $cleaned = strtoupper(preg_replace('/\s+/', '', $postcode));

        // UK postcodes are 5-7 characters (without space)
        if (strlen($cleaned) < 5 || strlen($cleaned) > 7) {
            return $cleaned;
        }

        // Insert space before last 3 characters
        return substr($cleaned, 0, -3).' '.substr($cleaned, -3);
    }

    public function validatePostcode(string $postcode): bool
    {
        $cleaned = preg_replace('/\s+/', '', strtoupper($postcode));

        return (bool) preg_match('/^[A-Z]{1,2}\d{1,2}[A-Z]?\s?\d[A-Z]{2}$/i', $cleaned);
    }
}
