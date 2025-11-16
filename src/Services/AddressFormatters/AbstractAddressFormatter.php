<?php

namespace CleaniqueCoders\Profile\Services\AddressFormatters;

use CleaniqueCoders\Profile\Contracts\CountryAddressFormatter;

abstract class AbstractAddressFormatter implements CountryAddressFormatter
{
    /**
     * Standardize address line (capitalize first letter of each word).
     */
    public function standardizeAddressLine(string $line): string
    {
        // Trim and remove multiple spaces
        $cleaned = preg_replace('/\s+/', ' ', trim($line));

        // Capitalize first letter of each word
        return mb_convert_case($cleaned, MB_CASE_TITLE);
    }

    /**
     * Standardize city name.
     */
    public function standardizeCity(string $city): string
    {
        return $this->standardizeAddressLine($city);
    }

    /**
     * Standardize state name.
     */
    public function standardizeState(string $state): string
    {
        return $this->standardizeAddressLine($state);
    }

    /**
     * Get state abbreviation (default: not supported).
     */
    public function getStateAbbreviation(string $state): ?string
    {
        return null;
    }

    /**
     * Get full state name from abbreviation (default: not supported).
     */
    public function getStateFullName(string $abbreviation): ?string
    {
        return null;
    }

    /**
     * Standardize full address components.
     */
    public function standardizeAddress(array $addressComponents): array
    {
        $standardized = [];

        if (isset($addressComponents['primary'])) {
            $standardized['primary'] = $this->standardizeAddressLine($addressComponents['primary']);
        }

        if (isset($addressComponents['secondary'])) {
            $standardized['secondary'] = $this->standardizeAddressLine($addressComponents['secondary']);
        }

        if (isset($addressComponents['city'])) {
            $standardized['city'] = $this->standardizeCity($addressComponents['city']);
        }

        if (isset($addressComponents['state'])) {
            $standardized['state'] = $this->standardizeState($addressComponents['state']);
        }

        if (isset($addressComponents['postcode'])) {
            $standardized['postcode'] = $this->formatPostcode($addressComponents['postcode']);
        }

        return array_merge($addressComponents, $standardized);
    }

    /**
     * Clean numeric-only postcode.
     */
    protected function cleanNumeric(string $postcode): string
    {
        return preg_replace('/[^0-9]/', '', $postcode);
    }

    /**
     * Clean alphanumeric postcode.
     */
    protected function cleanAlphanumeric(string $postcode): string
    {
        return preg_replace('/[^A-Z0-9]/i', '', strtoupper($postcode));
    }
}
