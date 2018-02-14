<?php

namespace CleaniqueCoders\Profile\Traits\Morphs;

/**
 * Phoneable Trait.
 */
trait Phoneable
{
    /**
     * Get all of the phones.
     */
    public function phones()
    {
        return $this->morphMany(\CleaniqueCoders\Profile\Models\Phone::class, 'phoneable');
    }
}
