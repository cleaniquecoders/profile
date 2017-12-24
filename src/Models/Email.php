<?php

namespace CLNQCDRS\Profile\Models;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $guarded = ['id'];

    /**
     * Get all of the owning emailable models.
     */
    public function emailable()
    {
        return $this->morphTo();
    }
}
