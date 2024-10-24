<?php

namespace CleaniqueCoders\Profile\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Websiteable Trait.
 */
trait Websiteable
{
    /**
     * Get all websites.
     */
    public function websites(): MorphMany
    {
        return $this->morphMany(
            config('profile.providers.website.model'),
            config('profile.providers.website.type')
        );
    }
}
