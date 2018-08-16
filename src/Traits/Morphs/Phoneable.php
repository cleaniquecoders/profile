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
        return $this->morphMany(\CleaniqueCoders\Profile\Models\Phone::class, 'phoneable');
    }
}
