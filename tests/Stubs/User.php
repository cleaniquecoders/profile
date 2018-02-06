<?php

namespace CleaniqueCoders\Profile\Tests\Stubs;

use CleaniqueCoders\Profile\Traits\HasProfile;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasProfile;
}
