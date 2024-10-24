<?php

namespace CleaniqueCoders\Profile\Concerns;

use CleaniqueCoders\Profile\Concerns\Addressable;
use CleaniqueCoders\Profile\Concerns\Emailable;
use CleaniqueCoders\Profile\Concerns\Phoneable;
use CleaniqueCoders\Profile\Concerns\Websiteable;

/**
 * HasProfile Trait.
 */
trait HasProfile 
{
    use Addressable;
    use Emailable;
    use Phoneable;
    use Websiteable;
}
