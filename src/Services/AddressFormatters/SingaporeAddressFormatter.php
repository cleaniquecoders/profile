<?php

namespace CleaniqueCoders\Profile\Services\AddressFormatters;

class SingaporeAddressFormatter extends AbstractAddressFormatter
{
    public function getCountryCode(): string
    {
        return 'SG';
    }

    public function formatPostcode(string $postcode): string
    {
        $cleaned = $this->cleanNumeric($postcode);

        if (strlen($cleaned) === 6) {
            return $cleaned;
        }

        // Pad with zeros if too short
        if (strlen($cleaned) < 6) {
            return str_pad($cleaned, 6, '0', STR_PAD_LEFT);
        }

        return substr($cleaned, 0, 6);
    }

    public function validatePostcode(string $postcode): bool
    {
        $cleaned = preg_replace('/\s+/', '', $postcode);

        return (bool) preg_match('/^\d{6}$/', $cleaned);
    }
}
