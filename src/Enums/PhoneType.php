<?php

namespace CleaniqueCoders\Profile\Enums;

use CleaniqueCoders\Traitify\Concerns\InteractsWithEnum;

enum PhoneType: string
{
    use InteractsWithEnum;

    case HOME = 'home';
    case MOBILE = 'mobile';
    case OFFICE = 'office';
    case FAX = 'fax';
    case OTHER = 'other';

    /**
     * Get the label for the phone type.
     */
    public function label(): string
    {
        return match ($this) {
            self::HOME => 'Home',
            self::MOBILE => 'Mobile',
            self::OFFICE => 'Office',
            self::FAX => 'Fax',
            self::OTHER => 'Other',
        };
    }

    /**
     * Get the description for the phone type.
     */
    public function description(): string
    {
        return match ($this) {
            self::HOME => 'Home phone number',
            self::MOBILE => 'Mobile/cell phone number',
            self::OFFICE => 'Office/work phone number',
            self::FAX => 'Fax number',
            self::OTHER => 'Other phone number type',
        };
    }
}
