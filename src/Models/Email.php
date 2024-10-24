<?php

namespace CleaniqueCoders\Profile\Models;

use CleaniqueCoders\Traitify\Concerns\InteractsWithUuid;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use InteractsWithUuid;

    protected $guarded = [
        'id',
    ];

    /**
     * Get all of the owning emailable models.
     */
    public function emailable()
    {
        return $this->morphTo();
    }
}
