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
        'id'
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
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(PhoneType::class, 'phone_type_id')->withDefault();
    }

    /**
     * Get Home Phone Numbers.
     *
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHome(Builder $query): Builder
    {
        return $query->where('phone_type_id', PhoneType::HOME);
    }

    /**
     * Get Mobile Phone Numbers.
     *
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMobile(Builder $query): Builder
    {
        return $query->where('phone_type_id', PhoneType::MOBILE);
    }

    /**
     * Get Office Phone Numbers.
     *
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOffice(Builder $query): Builder
    {
        return $query->where('phone_type_id', PhoneType::OFFICE);
    }

    /**
     * Get Other Phone Numbers.
     *
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOther(Builder $query): Builder
    {
        return $query->where('phone_type_id', PhoneType::OTHER);
    }

    /**
     * Get Fax Phone Numbers.
     *
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFax(Builder $query): Builder
    {
        return $query->where('phone_type_id', PhoneType::FAX);
    }
}
