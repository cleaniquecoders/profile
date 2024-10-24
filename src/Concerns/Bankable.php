<?php

namespace CleaniqueCoders\Profile\Concerns;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Bankable Trait.
 */
trait Bankable
{
    /**
     * Get bank.
     */
    public function bank(): BelongsTo
    {
        return $this->belongsTo(
            config('profile.profile.providers.bank.model')
        );
    }

    /**
     * Get all banks.
     */
    public function banks(): MorphMany
    {
        return $this->morphMany(
            config('profile.providers.bank.model'),
            config('profile.providers.bank.type')
        );
    }
}
