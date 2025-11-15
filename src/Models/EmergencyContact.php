<?php

namespace CleaniqueCoders\Profile\Models;

use CleaniqueCoders\Traitify\Concerns\InteractsWithUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmergencyContact extends Model
{
    use InteractsWithUuid, SoftDeletes;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    /**
     * Get all of the owning contactable models.
     */
    public function contactable()
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to only include primary emergency contacts.
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Scope a query to filter by relationship type.
     */
    public function scopeRelationship($query, string $type)
    {
        return $query->where('relationship_type', $type);
    }
}
