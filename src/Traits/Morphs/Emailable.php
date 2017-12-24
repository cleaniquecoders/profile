<?php

namespace CLNQCDRS\Profile\Traits\Morphs;

/**
 * Emailable Trait
 */
trait Emailable
{
    /**
     * Get all of the emails.
     */
    public function emails()
    {
        return $this->morphMany(\CLNQCDRS\Profile\Models\Email::class, 'emailable');
    }
}
