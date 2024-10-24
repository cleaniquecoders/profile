<?php

namespace CleaniqueCoders\Profile\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Emailable Trait.
 */
trait Emailable
{
    /**
     * Get all emails.
     */
    public function emails(): MorphMany
    {
        return $this->morphMany(
            config('profile.providers.email.model'),
            config('profile.providers.email.type')
        );
    }
}
