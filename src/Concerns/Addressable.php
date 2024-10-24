<?php

namespace CleaniqueCoders\Profile\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Addressable Trait.
 */
trait Addressable
{
    /**
     * Get all addresses.
     */
    public function addresses(): MorphMany
    {
        return $this->morphMany(
            config('profile.providers.address.model'),
            config('profile.providers.address.type')
        );
    }
}
