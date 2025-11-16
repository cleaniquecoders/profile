<?php

namespace CleaniqueCoders\Profile\Services\AddressFormatters;

class MalaysiaAddressFormatter extends AbstractAddressFormatter
{
    public function getCountryCode(): string
    {
        return 'MY';
    }

    public function formatPostcode(string $postcode): string
    {
        $cleaned = $this->cleanNumeric($postcode);

        if (strlen($cleaned) === 5) {
            return $cleaned;
        }

        // Pad with zeros if too short
        if (strlen($cleaned) < 5) {
            return str_pad($cleaned, 5, '0', STR_PAD_LEFT);
        }

        // Truncate if too long
        return substr($cleaned, 0, 5);
    }

    public function validatePostcode(string $postcode): bool
    {
        $cleaned = preg_replace('/\s+/', '', $postcode);

        return (bool) preg_match('/^\d{5}$/', $cleaned);
    }
}
