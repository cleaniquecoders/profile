<?php

namespace CleaniqueCoders\Profile\Models;

use CleaniqueCoders\Traitify\Concerns\InteractsWithUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class BankAccount extends Model
{
    use InteractsWithUuid;

    protected $guarded = [
        'id',
    ];

    /**
     * Bank.
     */
    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class)->withDefault();
    }

    /**
     * Get all of the owning bank models.
     */
    public function bankable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get Bank Name via Accessor.
     */
    public function getBankNameAttribute(): string
    {
        return $this->bank->name;
    }
}
