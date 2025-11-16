<?php

namespace CleaniqueCoders\Profile\Models;

use CleaniqueCoders\Profile\Services\PhoneFormatter;
use CleaniqueCoders\Traitify\Concerns\InteractsWithUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Phone extends Model
{
    use InteractsWithUuid;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'verification_code_expires_at' => 'datetime',
        'is_default' => 'boolean',
    ];

    /**
     * Get all of the owning phoneable models.
     */
    public function phoneable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Phone Type.
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(PhoneType::class, 'phone_type_id')->withDefault();
    }

    /**
     * Get Home Phone Numbers.
     */
    public function scopeHome(Builder $query): Builder
    {
        return $query->where('phone_type_id', PhoneType::HOME);
    }

    /**
     * Get Mobile Phone Numbers.
     */
    public function scopeMobile(Builder $query): Builder
    {
        return $query->where('phone_type_id', PhoneType::MOBILE);
    }

    /**
     * Get Office Phone Numbers.
     */
    public function scopeOffice(Builder $query): Builder
    {
        return $query->where('phone_type_id', PhoneType::OFFICE);
    }

    /**
     * Get Other Phone Numbers.
     */
    public function scopeOther(Builder $query): Builder
    {
        return $query->where('phone_type_id', PhoneType::OTHER);
    }

    /**
     * Get Fax Phone Numbers.
     */
    public function scopeFax(Builder $query): Builder
    {
        return $query->where('phone_type_id', PhoneType::FAX);
    }

    /**
     * Generate a 6-digit OTP verification code for the phone.
     *
     * @param  int  $expiresInMinutes  Default is 10 minutes
     */
    public function generateVerificationCode(int $expiresInMinutes = 10): self
    {
        $this->verification_code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->verification_code_expires_at = now()->addMinutes($expiresInMinutes);
        $this->save();

        return $this;
    }

    /**
     * Verify the phone using the provided OTP code.
     */
    public function verify(string $code): bool
    {
        if ($this->verification_code !== $code) {
            return false;
        }

        if ($this->verification_code_expires_at && $this->verification_code_expires_at->isPast()) {
            return false;
        }

        $this->verified_at = now();
        $this->verification_code = null;
        $this->verification_code_expires_at = null;
        $this->save();

        return true;
    }

    /**
     * Check if the phone is verified.
     */
    public function isVerified(): bool
    {
        return ! is_null($this->verified_at);
    }

    /**
     * Check if the phone is not verified.
     */
    public function isUnverified(): bool
    {
        return is_null($this->verified_at);
    }

    /**
     * Mark the phone as verified without a code.
     */
    public function markAsVerified(): self
    {
        $this->verified_at = now();
        $this->verification_code = null;
        $this->verification_code_expires_at = null;
        $this->save();

        return $this;
    }

    /**
     * Scope to get only verified phones.
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('verified_at');
    }

    /**
     * Scope to get only unverified phones.
     */
    public function scopeUnverified($query)
    {
        return $query->whereNull('verified_at');
    }

    /**
     * Format phone number to E.164 format.
     */
    public function toE164(?string $countryCode = null): string
    {
        return PhoneFormatter::toE164($this->number, $countryCode ?? '60');
    }

    /**
     * Format phone number to national format.
     */
    public function toNational(?string $countryCode = null): string
    {
        return PhoneFormatter::toNational($this->number, $countryCode ?? '60');
    }

    /**
     * Format phone number to readable format.
     */
    public function toReadable(?string $countryCode = null): string
    {
        return PhoneFormatter::toReadable($this->number, $countryCode ?? '60');
    }

    /**
     * Standardize the phone number and save it.
     */
    public function standardize(?string $countryCode = null): self
    {
        $this->number = PhoneFormatter::standardize($this->number, $countryCode);
        $this->save();

        return $this;
    }
}
