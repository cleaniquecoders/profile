<?php

namespace CleaniqueCoders\Profile\Traits\Morphs;

/**
 * Websiteable Trait
 */
trait Websiteable
{
    /**
     * Get all of the websites
     */
    public function websites()
    {
        return $this->morphMany(\CleaniqueCoders\Profile\Models\Website::class, 'websiteable');
    }
}
