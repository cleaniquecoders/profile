<?php

namespace CleaniqueCoders\Profile\Contracts;

interface CountryAddressFormatter
{
    /**
     * Get the country code this formatter handles.
     */
    public function getCountryCode(): string;

    /**
     * Format postcode for this country.
     */
    public function formatPostcode(string $postcode): string;

    /**
     * Validate postcode format for this country.
     */
    public function validatePostcode(string $postcode): bool;

    /**
     * Standardize address line.
     */
    public function standardizeAddressLine(string $line): string;

    /**
     * Standardize city name.
     */
    public function standardizeCity(string $city): string;

    /**
     * Standardize state/province name.
     */
    public function standardizeState(string $state): string;

    /**
     * Get state abbreviation (if applicable).
     */
    public function getStateAbbreviation(string $state): ?string;

    /**
     * Get full state name from abbreviation (if applicable).
     */
    public function getStateFullName(string $abbreviation): ?string;

    /**
     * Standardize full address components.
     */
    public function standardizeAddress(array $addressComponents): array;
}
