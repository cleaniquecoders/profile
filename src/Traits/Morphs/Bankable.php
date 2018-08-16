<?php

namespace CleaniqueCoders\Profile\Traits\Morphs;

/**
 * Bankable Trait.
 */
trait Bankable
{
    /**
     * Get all banks.
     */
    public function banks()
    {
        return $this->morphMany(\CleaniqueCoders\Profile\Models\Bank::class, 'bankable');
    }
}
