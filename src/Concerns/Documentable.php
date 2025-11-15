<?php

namespace CleaniqueCoders\Profile\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Documentable Trait.
 */
trait Documentable
{
    /**
     * Get all documents.
     */
    public function documents(): MorphMany
    {
        return $this->morphMany(
            config('profile.providers.document.model'),
            config('profile.providers.document.type')
        );
    }

    /**
     * Get active (not expired) documents.
     */
    public function activeDocuments()
    {
        return $this->documents()
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->get();
    }

    /**
     * Get expired documents.
     */
    public function expiredDocuments()
    {
        return $this->documents()
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->get();
    }

    /**
     * Get documents by type.
     */
    public function getDocumentsByType(string $type)
    {
        return $this->documents()->where('type', $type)->get();
    }

    /**
     * Check if has active documents.
     */
    public function hasActiveDocuments(): bool
    {
        return $this->documents()
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->exists();
    }

    /**
     * Check if has documents of a specific type.
     */
    public function hasDocument(string $type): bool
    {
        return $this->documents()->where('type', $type)->exists();
    }
}
