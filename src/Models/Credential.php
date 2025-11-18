<?php

namespace CleaniqueCoders\Profile\Models;

use CleaniqueCoders\Profile\Enums\CredentialType;
use CleaniqueCoders\Traitify\Concerns\InteractsWithUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Credential extends Model
{
    use InteractsWithUuid, SoftDeletes;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'type' => CredentialType::class,
        'is_verified' => 'boolean',
        'issued_at' => 'date',
        'expires_at' => 'date',
    ];

    /**
     * Get all of the owning credentialable models.
     */
    public function credentialable()
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to only include verified credentials.
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope a query to filter by credential type.
     */
    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to only include active (not expired) credentials.
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
                ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Scope a query to only include expired credentials.
     */
    public function scopeExpired($query)
    {
        return $query->whereNotNull('expires_at')
            ->where('expires_at', '<=', now());
    }

    /**
     * Scope a query to filter by credential category.
     *
     * @param  string  $category  One of: 'education', 'recognition', 'association', 'regulatory'
     */
    public function scopeCategory($query, string $category)
    {
        $types = collect(CredentialType::cases())
            ->filter(fn ($type) => $type->category() === $category)
            ->map(fn ($type) => $type->value)
            ->toArray();

        return $query->whereIn('type', $types);
    }

    /**
     * Get the category for this credential.
     *
     * @return string One of: 'education', 'recognition', 'association', 'regulatory'
     */
    public function getCategory(): string
    {
        return $this->type->category();
    }
}
