<?php

namespace CleaniqueCoders\Profile\Enums;

use CleaniqueCoders\Traitify\Concerns\InteractsWithEnum;

enum RelationshipType: string
{
    use InteractsWithEnum;

    case SPOUSE = 'spouse';
    case PARTNER = 'partner';
    case PARENT = 'parent';
    case FATHER = 'father';
    case MOTHER = 'mother';
    case SIBLING = 'sibling';
    case BROTHER = 'brother';
    case SISTER = 'sister';
    case CHILD = 'child';
    case SON = 'son';
    case DAUGHTER = 'daughter';
    case GRANDPARENT = 'grandparent';
    case GRANDCHILD = 'grandchild';
    case FRIEND = 'friend';
    case COLLEAGUE = 'colleague';
    case NEIGHBOR = 'neighbor';
    case GUARDIAN = 'guardian';
    case OTHER = 'other';

    /**
     * Get the label for the relationship type.
     */
    public function label(): string
    {
        return match ($this) {
            self::SPOUSE => 'Spouse',
            self::PARTNER => 'Partner',
            self::PARENT => 'Parent',
            self::FATHER => 'Father',
            self::MOTHER => 'Mother',
            self::SIBLING => 'Sibling',
            self::BROTHER => 'Brother',
            self::SISTER => 'Sister',
            self::CHILD => 'Child',
            self::SON => 'Son',
            self::DAUGHTER => 'Daughter',
            self::GRANDPARENT => 'Grandparent',
            self::GRANDCHILD => 'Grandchild',
            self::FRIEND => 'Friend',
            self::COLLEAGUE => 'Colleague',
            self::NEIGHBOR => 'Neighbor',
            self::GUARDIAN => 'Guardian',
            self::OTHER => 'Other',
        };
    }

    /**
     * Get the description for the relationship type.
     */
    public function description(): string
    {
        return match ($this) {
            self::SPOUSE => 'Married spouse',
            self::PARTNER => 'Domestic partner',
            self::PARENT => 'Parent (general)',
            self::FATHER => 'Father',
            self::MOTHER => 'Mother',
            self::SIBLING => 'Sibling (general)',
            self::BROTHER => 'Brother',
            self::SISTER => 'Sister',
            self::CHILD => 'Child (general)',
            self::SON => 'Son',
            self::DAUGHTER => 'Daughter',
            self::GRANDPARENT => 'Grandparent',
            self::GRANDCHILD => 'Grandchild',
            self::FRIEND => 'Friend',
            self::COLLEAGUE => 'Work colleague',
            self::NEIGHBOR => 'Neighbor',
            self::GUARDIAN => 'Legal guardian',
            self::OTHER => 'Other relationship',
        };
    }

    /**
     * Check if this is a family relationship.
     */
    public function isFamily(): bool
    {
        return in_array($this, [
            self::SPOUSE,
            self::PARTNER,
            self::PARENT,
            self::FATHER,
            self::MOTHER,
            self::SIBLING,
            self::BROTHER,
            self::SISTER,
            self::CHILD,
            self::SON,
            self::DAUGHTER,
            self::GRANDPARENT,
            self::GRANDCHILD,
        ]);
    }
}
