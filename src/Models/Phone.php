<?php

namespace CleaniqueCoders\Profile\Models;

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
}
