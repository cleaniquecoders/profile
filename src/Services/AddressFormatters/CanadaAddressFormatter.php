<?php

namespace CleaniqueCoders\Profile\Services\AddressFormatters;

class CanadaAddressFormatter extends AbstractAddressFormatter
{
    public function getCountryCode(): string
    {
        return 'CA';
    }

    public function formatPostcode(string $postcode): string
    {
        $cleaned = strtoupper(preg_replace('/\s+/', '', $postcode));

        if (strlen($cleaned) === 6) {
            return substr($cleaned, 0, 3).' '.substr($cleaned, 3);
        }

        return $cleaned;
    }

    public function validatePostcode(string $postcode): bool
    {
        $cleaned = preg_replace('/\s+/', '', strtoupper($postcode));

        return (bool) preg_match('/^[A-Z]\d[A-Z]\s?\d[A-Z]\d$/i', $cleaned);
    }
}
