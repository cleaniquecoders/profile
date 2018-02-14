<?php

namespace CleaniqueCoders\Profile\Traits\Morphs;

/**
 * Addressable Trait.
 */
trait Addressable
{
    /**
     * Get all of the addresses.
     */
    public function addresses()
    {
        return $this->morphMany(\CleaniqueCoders\Profile\Models\Address::class, 'addressable');
    }
}
