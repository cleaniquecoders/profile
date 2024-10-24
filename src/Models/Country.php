<?php

namespace CleaniqueCoders\Profile\Models;

use CleaniqueCoders\Traitify\Concerns\InteractsWithUuid;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use InteractsWithUuid;
    
    protected $guarded = [
        'id'
    ];
}
