<?php

namespace CleaniqueCoders\Profile\Traits\Morphs;

/**
 * Addressable Trait.
 */
trait Addressable
{
    /**
     * Get all addresses.
     */
    public function addresses()
    {
        return $this->morphMany(
            config('profile.providers.address.model'),
            config('profile.providers.address.type')
        );
    }
}
