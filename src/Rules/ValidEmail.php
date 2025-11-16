<?php

namespace CleaniqueCoders\Profile\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidEmail implements ValidationRule
{
    /**
     * Create a new rule instance.
     */
    public function __construct(
        protected bool $requireVerified = false
    ) {}

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Basic email format validation
        if (! filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $fail("The {$attribute} must be a valid email address.");

            return;
        }

        // Check if verification is required
        if ($this->requireVerified) {
            // This is a format validation rule, actual verification check
            // should be done at the model/application level
            // This just validates the email format
        }
    }

    /**
     * Create a new instance that requires verified email.
     */
    public static function verified(): self
    {
        return new self(requireVerified: true);
    }
}
