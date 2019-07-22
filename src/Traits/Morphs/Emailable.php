<?php

namespace CleaniqueCoders\Profile\Traits\Morphs;

/**
 * Emailable Trait.
 */
trait Emailable
{
    /**
     * Get all emails.
     */
    public function emails()
    {
        return $this->morphMany(
            config('profile.providers.email.model'),
            config('profile.providers.email.type')
        );
    }
}
