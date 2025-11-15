<?php

namespace CleaniqueCoders\Profile\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Credentialable Trait.
 */
trait Credentialable
{
    /**
     * Get all credentials.
     */
    public function credentials(): MorphMany
    {
        return $this->morphMany(
            config('profile.providers.credential.model'),
            config('profile.providers.credential.type')
        );
    }

    /**
     * Get active (not expired) credentials.
     */
    public function activeCredentials()
    {
        return $this->credentials()
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->get();
    }

    /**
     * Get expired credentials.
     */
    public function expiredCredentials()
    {
        return $this->credentials()
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->get();
    }

    /**
     * Get credentials by type.
     */
    public function getCredentialsByType(string $type)
    {
        return $this->credentials()->where('type', $type)->get();
    }

    /**
     * Check if has active credentials.
     */
    public function hasActiveCredentials(): bool
    {
        return $this->credentials()
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->exists();
    }

    /**
     * Check if has credentials of a specific type.
     */
    public function hasCredential(string $type): bool
    {
        return $this->credentials()->where('type', $type)->exists();
    }
}
