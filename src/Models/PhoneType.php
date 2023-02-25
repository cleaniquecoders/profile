<?php

namespace CleaniqueCoders\Profile\Models;

use Illuminate\Database\Eloquent\Model;

class PhoneType extends Model
{
    public const HOME = 1;

    public const MOBILE = 2;

    public const OFFICE = 3;

    public const OTHER = 4;

    public const FAX = 5;
}
