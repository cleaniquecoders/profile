<?php

namespace CleaniqueCoders\Profile\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidAddress implements ValidationRule
{
    /**
     * Create a new rule instance.
     */
    public function __construct(
        protected array $requiredFields = [],
        protected bool $requireCoordinates = false
    ) {}

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Value should be an array of address components
        if (! is_array($value)) {
            $fail("The {$attribute} must be an array of address components.");

            return;
        }

        // Check required fields
        foreach ($this->requiredFields as $field) {
            if (empty($value[$field])) {
                $fail("The {$attribute} must include a {$field}.");

                return;
            }
        }

        // Check coordinates if required
        if ($this->requireCoordinates) {
            if (empty($value['latitude']) || empty($value['longitude'])) {
                $fail("The {$attribute} must include valid coordinates (latitude and longitude).");

                return;
            }

            // Validate latitude range (-90 to 90)
            if ($value['latitude'] < -90 || $value['latitude'] > 90) {
                $fail("The {$attribute} latitude must be between -90 and 90.");

                return;
            }

            // Validate longitude range (-180 to 180)
            if ($value['longitude'] < -180 || $value['longitude'] > 180) {
                $fail("The {$attribute} longitude must be between -180 and 180.");

                return;
            }
        }
    }

    /**
     * Create a new instance requiring specific fields.
     */
    public static function requiring(array $fields): self
    {
        return new self(requiredFields: $fields);
    }

    /**
     * Create a new instance requiring coordinates.
     */
    public static function withCoordinates(): self
    {
        return new self(requireCoordinates: true);
    }

    /**
     * Create a new instance requiring complete address.
     */
    public static function complete(): self
    {
        return new self(requiredFields: ['primary', 'city', 'state', 'postcode']);
    }
}
