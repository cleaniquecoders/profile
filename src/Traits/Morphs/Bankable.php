<?php

namespace CleaniqueCoders\Profile\Traits\Morphs;

/**
 * Bankable Trait.
 */
trait Bankable
{
    /**
     * Get bank.
     */
    public function bank()
    {
        return $this->belongsTo(
            config('profile.profile.providers.bank.model')
        );
    }

    /**
     * Get all banks.
     */
    public function banks()
    {
        return $this->morphMany(
            config('profile.providers.bank.model'),
            config('profile.providers.bank.type')
        );
    }
}
