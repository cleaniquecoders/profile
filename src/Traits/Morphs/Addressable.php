<?php

namespace CLNQCDRS\Profile\Traits\Morphs;

/**
 * Addressable Trait
 */
trait Addressable
{
    /**
     * Get all of the addresses
     */
    public function addresses()
    {
        return $this->morphMany(\CLNQCDRS\Profile\Models\Address::class, 'addressable');
    }
}
