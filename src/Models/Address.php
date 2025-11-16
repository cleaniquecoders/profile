<?php

namespace CleaniqueCoders\Profile\Models;

use CleaniqueCoders\Profile\Services\AddressFormatter;
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

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'validated_at' => 'datetime',
        'is_default' => 'boolean',
    ];

    protected $attributes = [
        'validation_status' => 'pending',
    ];

    /**
     * Validation status constants.
     */
    const STATUS_PENDING = 'pending';

    const STATUS_VALID = 'valid';

    const STATUS_INVALID = 'invalid';

    const STATUS_PARTIAL = 'partial';

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

    /**
     * Set the coordinates for the address.
     */
    public function setCoordinates(float $latitude, float $longitude): self
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->save();

        return $this;
    }

    /**
     * Mark the address as validated.
     */
    public function markAsValid(): self
    {
        $this->validation_status = self::STATUS_VALID;
        $this->validated_at = now();
        $this->save();

        return $this;
    }

    /**
     * Mark the address as invalid.
     */
    public function markAsInvalid(): self
    {
        $this->validation_status = self::STATUS_INVALID;
        $this->validated_at = now();
        $this->save();

        return $this;
    }

    /**
     * Mark the address as partially valid.
     */
    public function markAsPartial(): self
    {
        $this->validation_status = self::STATUS_PARTIAL;
        $this->validated_at = now();
        $this->save();

        return $this;
    }

    /**
     * Reset validation status to pending.
     */
    public function resetValidation(): self
    {
        $this->validation_status = self::STATUS_PENDING;
        $this->validated_at = null;
        $this->save();

        return $this;
    }

    /**
     * Check if the address is valid.
     */
    public function isValid(): bool
    {
        return $this->validation_status === self::STATUS_VALID;
    }

    /**
     * Check if the address is invalid.
     */
    public function isInvalid(): bool
    {
        return $this->validation_status === self::STATUS_INVALID;
    }

    /**
     * Check if the address is pending validation.
     */
    public function isPending(): bool
    {
        return $this->validation_status === self::STATUS_PENDING;
    }

    /**
     * Check if the address has coordinates.
     */
    public function hasCoordinates(): bool
    {
        return ! is_null($this->latitude) && ! is_null($this->longitude);
    }

    /**
     * Get the full address as a string.
     */
    public function getFullAddress(): string
    {
        $parts = array_filter([
            $this->primary,
            $this->secondary,
            $this->city,
            $this->state,
            $this->postcode,
            $this->country?->name,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Scope to get only validated addresses.
     */
    public function scopeValidated($query)
    {
        return $query->whereNotNull('validated_at');
    }

    /**
     * Scope to get only pending addresses.
     */
    public function scopePending($query)
    {
        return $query->where('validation_status', self::STATUS_PENDING);
    }

    /**
     * Scope to get only valid addresses.
     */
    public function scopeValid($query)
    {
        return $query->where('validation_status', self::STATUS_VALID);
    }

    /**
     * Scope to get only invalid addresses.
     */
    public function scopeInvalid($query)
    {
        return $query->where('validation_status', self::STATUS_INVALID);
    }

    /**
     * Scope to get addresses with coordinates.
     */
    public function scopeWithCoordinates($query)
    {
        return $query->whereNotNull('latitude')->whereNotNull('longitude');
    }

    /**
     * Standardize the address and save it.
     */
    public function standardize(): self
    {
        $countryCode = $this->country?->code ?? 'MY';

        if ($this->primary) {
            $this->primary = AddressFormatter::standardizeAddressLine($this->primary, $countryCode);
        }

        if ($this->secondary) {
            $this->secondary = AddressFormatter::standardizeAddressLine($this->secondary, $countryCode);
        }

        if ($this->city) {
            $this->city = AddressFormatter::standardizeCity($this->city, $countryCode);
        }

        if ($this->state) {
            $this->state = AddressFormatter::standardizeState($this->state, $countryCode);
        }

        if ($this->postcode) {
            $this->postcode = AddressFormatter::standardizePostcode($this->postcode, $countryCode);
        }

        $this->save();

        return $this;
    }

    /**
     * Get formatted postcode.
     */
    public function getFormattedPostcode(): string
    {
        if (! $this->postcode) {
            return '';
        }

        $countryCode = $this->country?->code ?? 'MY';

        return AddressFormatter::standardizePostcode($this->postcode, $countryCode);
    }
}
