<?php

namespace CleaniqueCoders\Profile\Models;

use CleaniqueCoders\Traitify\Concerns\InteractsWithUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Website extends Model
{
    use InteractsWithUuid;
    
    protected $guarded = [
        'id'
    ];

    /**
     * Get all of the owning websiteable models.
     */
    public function websiteable(): MorphTo
    {
        return $this->morphTo();
    }
}
