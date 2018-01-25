<?php

namespace CleaniqueCoders\Profile\Models;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    protected $guarded = ['id'];

    /**
     * Get all of the owning phoneable models.
     */
    public function phoneable()
    {
        return $this->morphTo();
    }
}
