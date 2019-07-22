<?php

namespace CleaniqueCoders\Profile\Traits\Morphs;

/**
 * Phoneable Trait.
 */
trait Phoneable
{
    /**
     * Get all phones.
     */
    public function phones()
    {
        return $this->morphMany(
            config('profile.providers.phone.model'),
            config('profile.providers.phone.type')
        );
    }
}
