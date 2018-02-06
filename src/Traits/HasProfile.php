<?php

namespace CleaniqueCoders\Profile\Traits;

use CleaniqueCoders\Profile\Traits\Morphs\Addressable;
use CleaniqueCoders\Profile\Traits\Morphs\Emailable;
use CleaniqueCoders\Profile\Traits\Morphs\Phoneable;
use CleaniqueCoders\Profile\Traits\Morphs\Websiteable;

/**
 * HasProfile Trait
 */
trait HasProfile
{
    use Addressable, Emailable, Phoneable, Websiteable;
}
