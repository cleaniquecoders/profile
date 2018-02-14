<?php

namespace CleaniqueCoders\Profile\Traits\Morphs;

/**
 * Emailable Trait.
 */
trait Emailable
{
    /**
     * Get all of the emails.
     */
    public function emails()
    {
        return $this->morphMany(\CleaniqueCoders\Profile\Models\Email::class, 'emailable');
    }
}
