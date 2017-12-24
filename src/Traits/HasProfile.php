<?php

namespace CLNQCDRS\Profile\Traits;

use CLNQCDRS\Profile\Traits\Morphs\Addressable;
use CLNQCDRS\Profile\Traits\Morphs\Emailable;
use CLNQCDRS\Profile\Traits\Morphs\Phoneable;

/**
 * HasProfile Trait
 */
trait HasProfile
{
    use Addressable, Emailable, Phoneable;
}
