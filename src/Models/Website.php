<?php

namespace CleaniqueCoders\Profile\Models;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    protected $guarded = [];

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
     * Get all of the owning websiteable models.
     */
    public function websiteable()
    {
        return $this->morphTo();
    }
}
