<?php

namespace CleaniqueCoders\Profile\Traits;

use CleaniqueCoders\Profile\Traits\Morphs\Addressable;
use CleaniqueCoders\Profile\Traits\Morphs\Emailable;
use CleaniqueCoders\Profile\Traits\Morphs\Phoneable;

/**
 * HasProfile Trait
 */
trait HasProfile
{
    use Addressable, Emailable, Phoneable;
}
