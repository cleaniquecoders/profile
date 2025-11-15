<?php

namespace CleaniqueCoders\Profile\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RelationshipTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $relationshipTypes = [
            'spouse' => 'Spouse',
            'partner' => 'Partner',
            'parent' => 'Parent',
            'father' => 'Father',
            'mother' => 'Mother',
            'sibling' => 'Sibling',
            'brother' => 'Brother',
            'sister' => 'Sister',
            'child' => 'Child',
            'son' => 'Son',
            'daughter' => 'Daughter',
            'grandparent' => 'Grandparent',
            'grandchild' => 'Grandchild',
            'friend' => 'Friend',
            'colleague' => 'Colleague',
            'neighbor' => 'Neighbor',
            'guardian' => 'Guardian',
            'other' => 'Other',
        ];

        // This seeder is for reference purposes
        // The actual relationship type values are stored directly in the emergency_contacts table
        // You can use this data to create a relationship_types reference table if needed
    }

    /**
     * Get all available relationship types.
     */
    public static function getRelationshipTypes(): array
    {
        return [
            'spouse' => 'Spouse',
            'partner' => 'Partner',
            'parent' => 'Parent',
            'father' => 'Father',
            'mother' => 'Mother',
            'sibling' => 'Sibling',
            'brother' => 'Brother',
            'sister' => 'Sister',
            'child' => 'Child',
            'son' => 'Son',
            'daughter' => 'Daughter',
            'grandparent' => 'Grandparent',
            'grandchild' => 'Grandchild',
            'friend' => 'Friend',
            'colleague' => 'Colleague',
            'neighbor' => 'Neighbor',
            'guardian' => 'Guardian',
            'other' => 'Other',
        ];
    }
}
