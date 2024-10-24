<?php

namespace CleaniqueCoders\Profile\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Phoneable Trait.
 */
trait Phoneable
{
    /**
     * Get all phones.
     */
    public function phones(): MorphMany
    {
        return $this->morphMany(
            config('profile.providers.phone.model'),
            config('profile.providers.phone.type')
        );
    }
}
