<?php

namespace CleaniqueCoders\Profile\Models;

use CleaniqueCoders\Profile\Concerns\HasProfile;
use CleaniqueCoders\Traitify\Concerns\InteractsWithUuid;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasProfile, InteractsWithUuid;

    protected $guarded = [
        'id'
    ];
}
