<?php

namespace CleaniqueCoders\Profile\Models;

use CleaniqueCoders\Profile\Services\EmailNormalizer;
use CleaniqueCoders\Traitify\Concerns\InteractsWithUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Email extends Model
{
    use InteractsWithUuid;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'verification_token_expires_at' => 'datetime',
        'is_default' => 'boolean',
    ];

    /**
     * Get all of the owning emailable models.
     */
    public function emailable()
    {
        return $this->morphTo();
    }

    /**
     * Generate a verification token for the email.
     *
     * @param  int  $expiresInMinutes  Default is 60 minutes
     */
    public function generateVerificationToken(int $expiresInMinutes = 60): self
    {
        $this->verification_token = Str::random(64);
        $this->verification_token_expires_at = now()->addMinutes($expiresInMinutes);
        $this->save();

        return $this;
    }

    /**
     * Verify the email using the provided token.
     */
    public function verify(string $token): bool
    {
        if ($this->verification_token !== $token) {
            return false;
        }

        if ($this->verification_token_expires_at && $this->verification_token_expires_at->isPast()) {
            return false;
        }

        $this->verified_at = now();
        $this->verification_token = null;
        $this->verification_token_expires_at = null;
        $this->save();

        return true;
    }

    /**
     * Check if the email is verified.
     */
    public function isVerified(): bool
    {
        return ! is_null($this->verified_at);
    }

    /**
     * Check if the email is not verified.
     */
    public function isUnverified(): bool
    {
        return is_null($this->verified_at);
    }

    /**
     * Mark the email as verified without a token.
     */
    public function markAsVerified(): self
    {
        $this->verified_at = now();
        $this->verification_token = null;
        $this->verification_token_expires_at = null;
        $this->save();

        return $this;
    }

    /**
     * Scope to get only verified emails.
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('verified_at');
    }

    /**
     * Scope to get only unverified emails.
     */
    public function scopeUnverified($query)
    {
        return $query->whereNull('verified_at');
    }

    /**
     * Normalize the email address.
     */
    public function normalize(bool $removeDots = false, bool $removePlusAddressing = false): string
    {
        return EmailNormalizer::normalize($this->email, $removeDots, $removePlusAddressing);
    }

    /**
     * Get the canonical form of the email.
     */
    public function getCanonical(): string
    {
        return EmailNormalizer::canonical($this->email);
    }

    /**
     * Get the email domain.
     */
    public function getDomain(): ?string
    {
        return EmailNormalizer::getDomain($this->email);
    }

    /**
     * Get the email provider.
     */
    public function getProvider(): ?string
    {
        return EmailNormalizer::getProvider($this->email);
    }

    /**
     * Check if the email is from a business domain.
     */
    public function isBusinessEmail(): bool
    {
        return EmailNormalizer::isBusinessEmail($this->email);
    }

    /**
     * Check if the email is from a disposable email service.
     */
    public function isDisposable(): bool
    {
        return EmailNormalizer::isDisposable($this->email);
    }

    /**
     * Standardize the email and save it.
     */
    public function standardize(): self
    {
        $this->email = EmailNormalizer::normalize($this->email);
        $this->save();

        return $this;
    }
}
