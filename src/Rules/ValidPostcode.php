<?php

namespace CleaniqueCoders\Profile\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPostcode implements ValidationRule
{
    /**
     * Create a new rule instance.
     */
    public function __construct(
        protected ?string $countryCode = null
    ) {}

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return; // Allow empty values, use 'required' rule separately
        }

        // Country-specific postcode validation
        $valid = match ($this->countryCode) {
            'MY' => $this->validateMalaysianPostcode($value),
            'US' => $this->validateUSPostcode($value),
            'UK', 'GB' => $this->validateUKPostcode($value),
            'SG' => $this->validateSingaporePostcode($value),
            default => $this->validateGenericPostcode($value),
        };

        if (! $valid) {
            $fail("The {$attribute} is not a valid postcode".($this->countryCode ? " for {$this->countryCode}" : '').'.');
        }
    }

    /**
     * Validate Malaysian postcode (5 digits).
     */
    protected function validateMalaysianPostcode(string $value): bool
    {
        return (bool) preg_match('/^\d{5}$/', $value);
    }

    /**
     * Validate US postcode (ZIP code: 5 digits or 5+4 format).
     */
    protected function validateUSPostcode(string $value): bool
    {
        return (bool) preg_match('/^\d{5}(-\d{4})?$/', $value);
    }

    /**
     * Validate UK postcode.
     */
    protected function validateUKPostcode(string $value): bool
    {
        return (bool) preg_match('/^[A-Z]{1,2}\d{1,2}[A-Z]?\s?\d[A-Z]{2}$/i', $value);
    }

    /**
     * Validate Singapore postcode (6 digits).
     */
    protected function validateSingaporePostcode(string $value): bool
    {
        return (bool) preg_match('/^\d{6}$/', $value);
    }

    /**
     * Validate generic postcode (alphanumeric, 3-10 characters).
     */
    protected function validateGenericPostcode(string $value): bool
    {
        return (bool) preg_match('/^[A-Z0-9\s\-]{3,10}$/i', $value);
    }

    /**
     * Create a new instance for a specific country.
     */
    public static function for(string $countryCode): self
    {
        return new self(countryCode: strtoupper($countryCode));
    }
}
