<?php

namespace CleaniqueCoders\Profile\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Socialable Trait.
 */
trait Socialable
{
    /**
     * Get all social media accounts.
     */
    public function socialMedia(): MorphMany
    {
        return $this->morphMany(
            config('profile.providers.social_media.model'),
            config('profile.providers.social_media.type')
        );
    }

    /**
     * Get primary social media account.
     */
    public function primarySocialMedia()
    {
        return $this->socialMedia()->where('is_primary', true)->first();
    }

    /**
     * Get social media account by platform.
     */
    public function getSocialMediaByPlatform(string $platform)
    {
        return $this->socialMedia()->where('platform', $platform)->first();
    }

    /**
     * Check if has social media account for a specific platform.
     */
    public function hasSocialMedia(?string $platform = null): bool
    {
        if ($platform) {
            return $this->socialMedia()->where('platform', $platform)->exists();
        }

        return $this->socialMedia()->exists();
    }
}
