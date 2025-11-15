<?php

namespace CleaniqueCoders\Profile\Enums;

use CleaniqueCoders\Traitify\Concerns\InteractsWithEnum;

enum CredentialType: string
{
    use InteractsWithEnum;

    case LICENSE = 'license';
    case CERTIFICATION = 'certification';
    case DIPLOMA = 'diploma';
    case DEGREE = 'degree';
    case PERMIT = 'permit';
    case ACCREDITATION = 'accreditation';
    case REGISTRATION = 'registration';
    case MEMBERSHIP = 'membership';
    case AWARD = 'award';

    /**
     * Get the label for the credential type.
     */
    public function label(): string
    {
        return match ($this) {
            self::LICENSE => 'License',
            self::CERTIFICATION => 'Certification',
            self::DIPLOMA => 'Diploma',
            self::DEGREE => 'Degree',
            self::PERMIT => 'Permit',
            self::ACCREDITATION => 'Accreditation',
            self::REGISTRATION => 'Registration',
            self::MEMBERSHIP => 'Membership',
            self::AWARD => 'Award',
        };
    }

    /**
     * Get the description for the credential type.
     */
    public function description(): string
    {
        return match ($this) {
            self::LICENSE => 'Professional license',
            self::CERTIFICATION => 'Professional certification',
            self::DIPLOMA => 'Educational diploma',
            self::DEGREE => 'Academic degree',
            self::PERMIT => 'Work or professional permit',
            self::ACCREDITATION => 'Professional accreditation',
            self::REGISTRATION => 'Professional registration',
            self::MEMBERSHIP => 'Professional membership',
            self::AWARD => 'Professional award or recognition',
        };
    }

    /**
     * Check if this credential type typically expires.
     */
    public function typicallyExpires(): bool
    {
        return in_array($this, [
            self::LICENSE,
            self::CERTIFICATION,
            self::PERMIT,
            self::ACCREDITATION,
            self::REGISTRATION,
            self::MEMBERSHIP,
        ]);
    }
}
