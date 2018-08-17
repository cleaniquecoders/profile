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
        return $this->belongsTo(\CleaniqueCoders\Profile\Models\Bank::class);
    }

    /**
     * Get all banks.
     */
    public function banks()
    {
        return $this->morphMany(\CleaniqueCoders\Profile\Models\BankAccount::class, 'bankable');
    }
}
