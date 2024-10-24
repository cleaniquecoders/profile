<?php

namespace CleaniqueCoders\Profile\Models;

use CleaniqueCoders\Traitify\Concerns\InteractsWithUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Address extends Model
{
    use InteractsWithUuid;

    protected $guarded = [
        'id',
    ];

    /**
     * Get all of the owning addressable models.
     */
    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get Country.
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
