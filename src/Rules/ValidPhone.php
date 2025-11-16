<?php

namespace CleaniqueCoders\Profile\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPhone implements ValidationRule
{
    /**
     * Create a new rule instance.
     */
    public function __construct(
        protected bool $requireVerified = false,
        protected ?string $format = null
    ) {}

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Remove common formatting characters
        $cleaned = preg_replace('/[\s\-\(\)\.]+/', '', $value);

        // Check if it starts with + (international format)
        if (str_starts_with($cleaned, '+')) {
            // International format: should be + followed by 7-15 digits
            if (! preg_match('/^\+\d{7,15}$/', $cleaned)) {
                $fail("The {$attribute} must be a valid international phone number (e.g., +60123456789).");

                return;
            }
        } else {
            // Local format: should be 7-15 digits
            if (! preg_match('/^\d{7,15}$/', $cleaned)) {
                $fail("The {$attribute} must be a valid phone number.");

                return;
            }
        }

        // Additional format validation if specified
        if ($this->format && ! preg_match($this->format, $value)) {
            $fail("The {$attribute} does not match the required format.");
        }
    }

    /**
     * Create a new instance that requires verified phone.
     */
    public static function verified(): self
    {
        return new self(requireVerified: true);
    }

    /**
     * Create a new instance with custom format.
     */
    public static function withFormat(string $regex): self
    {
        return new self(format: $regex);
    }
}
