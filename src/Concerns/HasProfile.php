<?php

namespace CleaniqueCoders\Profile\Concerns;

/**
 * HasProfile Trait.
 */
trait HasProfile
{
    use Addressable;
    use Emailable;
    use Phoneable;
    use Websiteable;
    use Socialable;
    use EmergencyContactable;
    use Credentialable;
    use Documentable;
}
