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
        return $this->morphMany(\CleaniqueCoders\Profile\Models\Email::class, 'emailable');
    }
}
