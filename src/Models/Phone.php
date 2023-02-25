<?php

namespace CleaniqueCoders\Profile\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    protected $guarded = ['id'];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'hashslug';
    }

    /**
     * Get all of the owning phoneable models.
     */
    public function phoneable()
    {
        return $this->morphTo();
    }

    /**
     * Phone Type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(PhoneType::class, 'phone_type_id')->withDefault();
    }

    /**
     * Get Home Phone Numbers.
     *
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHome(Builder $query)
    {
        return $query->where('phone_type_id', PhoneType::HOME);
    }

    /**
     * Get Mobile Phone Numbers.
     *
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMobile(Builder $query)
    {
        return $query->where('phone_type_id', PhoneType::MOBILE);
    }

    /**
     * Get Office Phone Numbers.
     *
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOffice(Builder $query)
    {
        return $query->where('phone_type_id', PhoneType::OFFICE);
    }

    /**
     * Get Other Phone Numbers.
     *
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOther(Builder $query)
    {
        return $query->where('phone_type_id', PhoneType::OTHER);
    }

    /**
     * Get Fax Phone Numbers.
     *
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFax(Builder $query)
    {
        return $query->where('phone_type_id', PhoneType::FAX);
    }
}
