<?php

namespace CleaniqueCoders\Profile\Models;

use Illuminate\Database\Eloquent\Model;

class PhoneType extends Model
{
    const HOME   = 1;
    const MOBILE = 2;
    const OFFICE = 3;
    const OTHER  = 4;
}
