<?php

namespace CleaniqueCoders\Profile\Models;

use CleaniqueCoders\Profile\Enums\SocialMediaPlatform;
use CleaniqueCoders\Traitify\Concerns\InteractsWithUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocialMedia extends Model
{
    use InteractsWithUuid, SoftDeletes;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'platform' => SocialMediaPlatform::class,
        'is_verified' => 'boolean',
        'is_primary' => 'boolean',
    ];

    /**
     * Get all of the owning socialable models.
     */
    public function socialable()
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to only include primary social media accounts.
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Scope a query to only include verified social media accounts.
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope a query to filter by platform.
     */
    public function scopePlatform($query, string $platform)
    {
        return $query->where('platform', $platform);
    }
}
