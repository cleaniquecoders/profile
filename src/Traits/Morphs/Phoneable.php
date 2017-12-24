<?php

namespace CLNQCDRS\Profile\Traits\Morphs;

/**
 * Phoneable Trait
 */
trait Phoneable
{
    /**
     * Get all of the phones.
     */
    public function phones()
    {
        return $this->morphMany(\CLNQCDRS\Profile\Models\Phone::class, 'phoneable');
    }
}
