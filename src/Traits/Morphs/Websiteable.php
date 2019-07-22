<?php

namespace CleaniqueCoders\Profile\Traits\Morphs;

/**
 * Websiteable Trait.
 */
trait Websiteable
{
    /**
     * Get all websites.
     */
    public function websites()
    {
        return $this->morphMany(
            config('profile.providers.website.model'),
            config('profile.providers.website.type')
        );
    }
}
