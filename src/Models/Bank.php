<?php

namespace CleaniqueCoders\Profile\Models;

use CleaniqueCoders\Profile\Traits\HasProfile;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasProfile;

    protected $guarded = ['id'];
}
