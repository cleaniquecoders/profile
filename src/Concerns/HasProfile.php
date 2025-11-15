<?php

namespace CleaniqueCoders\Profile\Concerns;

/**
 * HasProfile Trait.
 */
trait HasProfile
{
    use Addressable;
    use Credentialable;
    use Documentable;
    use Emailable;
    use EmergencyContactable;
    use Phoneable;
    use Socialable;
    use Websiteable;
}
