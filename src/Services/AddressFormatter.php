<?php

namespace CleaniqueCoders\Profile\Services;

use CleaniqueCoders\Profile\Contracts\CountryAddressFormatter;

class AddressFormatter
{
    protected static ?array $formatters = null;

    /**
     * Get all registered country formatters.
     */
    protected static function getFormatters(): array
    {
        if (static::$formatters === null) {
            static::$formatters = [];

            $formatters = config('profile.address_formatters', []);

            foreach ($formatters as $formatterClass) {
                if (class_exists($formatterClass)) {
                    $formatter = new $formatterClass;
                    if ($formatter instanceof CountryAddressFormatter) {
                        static::$formatters[$formatter->getCountryCode()] = $formatter;
                    }
                }
            }
        }

        return static::$formatters;
    }

    /**
     * Get formatter for a specific country.
     */
    protected static function getFormatter(string $countryCode): ?CountryAddressFormatter
    {
        $formatters = static::getFormatters();

        return $formatters[strtoupper($countryCode)] ?? null;
    }

    /**
     * Standardize postcode format based on country.
     */
    public static function standardizePostcode(string $postcode, string $countryCode = 'MY'): string
    {
        $formatter = static::getFormatter($countryCode);

        if ($formatter) {
            return $formatter->formatPostcode($postcode);
        }

        // Fallback: just clean and return
        return preg_replace('/[^A-Z0-9]/i', '', strtoupper($postcode));
    }

    /**
     * Validate postcode format based on country.
     */
    public static function isValidPostcode(string $postcode, string $countryCode = 'MY'): bool
    {
        $formatter = static::getFormatter($countryCode);

        if ($formatter) {
            return $formatter->validatePostcode($postcode);
        }

        // Fallback: check if not empty
        return strlen(trim($postcode)) > 0;
    }

    /**
     * Standardize address line.
     */
    public static function standardizeAddressLine(string $line, ?string $countryCode = null): string
    {
        if ($countryCode) {
            $formatter = static::getFormatter($countryCode);
            if ($formatter) {
                return $formatter->standardizeAddressLine($line);
            }
        }

        // Default implementation
        $cleaned = preg_replace('/\s+/', ' ', trim($line));

        return mb_convert_case($cleaned, MB_CASE_TITLE);
    }

    /**
     * Standardize city name.
     */
    public static function standardizeCity(string $city, ?string $countryCode = null): string
    {
        if ($countryCode) {
            $formatter = static::getFormatter($countryCode);
            if ($formatter) {
                return $formatter->standardizeCity($city);
            }
        }

        return static::standardizeAddressLine($city);
    }

    /**
     * Standardize state name.
     */
    public static function standardizeState(string $state, ?string $countryCode = null): string
    {
        if ($countryCode) {
            $formatter = static::getFormatter($countryCode);
            if ($formatter) {
                return $formatter->standardizeState($state);
            }
        }

        return static::standardizeAddressLine($state);
    }

    /**
     * Get state abbreviation (for countries that support it).
     */
    public static function getStateAbbreviation(string $state, string $countryCode = 'US'): ?string
    {
        $formatter = static::getFormatter($countryCode);

        if ($formatter) {
            return $formatter->getStateAbbreviation($state);
        }

        return null;
    }

    /**
     * Get full state name from abbreviation (for countries that support it).
     */
    public static function getStateFullName(string $abbreviation, string $countryCode = 'US'): ?string
    {
        $formatter = static::getFormatter($countryCode);

        if ($formatter) {
            return $formatter->getStateFullName($abbreviation);
        }

        return null;
    }

    /**
     * Standardize full address.
     */
    public static function standardizeAddress(array $addressComponents): array
    {
        $countryCode = $addressComponents['country_code'] ?? 'MY';
        $formatter = static::getFormatter($countryCode);

        if ($formatter) {
            return $formatter->standardizeAddress($addressComponents);
        }

        // Fallback implementation
        $standardized = [];

        if (isset($addressComponents['primary'])) {
            $standardized['primary'] = static::standardizeAddressLine($addressComponents['primary']);
        }

        if (isset($addressComponents['secondary'])) {
            $standardized['secondary'] = static::standardizeAddressLine($addressComponents['secondary']);
        }

        if (isset($addressComponents['city'])) {
            $standardized['city'] = static::standardizeCity($addressComponents['city']);
        }

        if (isset($addressComponents['state'])) {
            $standardized['state'] = static::standardizeState($addressComponents['state']);
        }

        if (isset($addressComponents['postcode'])) {
            $standardized['postcode'] = static::standardizePostcode(
                $addressComponents['postcode'],
                $countryCode
            );
        }

        return array_merge($addressComponents, $standardized);
    }

    /**
     * Register a custom country formatter.
     */
    public static function registerFormatter(CountryAddressFormatter $formatter): void
    {
        static::$formatters = static::$formatters ?? [];
        static::$formatters[$formatter->getCountryCode()] = $formatter;
    }

    /**
     * Get all available country codes.
     */
    public static function getAvailableCountries(): array
    {
        return array_keys(static::getFormatters());
    }
}
