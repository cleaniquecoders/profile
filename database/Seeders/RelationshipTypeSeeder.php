<?php

namespace CleaniqueCoders\Profile\Database\Seeders;

use CleaniqueCoders\Profile\Enums\RelationshipType;
use Illuminate\Database\Seeder;

class RelationshipTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // This seeder is for reference purposes
        // The actual relationship type values are stored directly in the emergency_contacts table
        // Relationship type values are defined in the RelationshipType enum
    }

    /**
     * Get all available relationship types.
     */
    public static function getRelationshipTypes(): array
    {
        return RelationshipType::values();
    }

    /**
     * Get relationship type labels.
     */
    public static function getRelationshipTypeLabels(): array
    {
        return RelationshipType::labels();
    }

    /**
     * Get relationship type options for select inputs.
     */
    public static function getRelationshipTypeOptions(): array
    {
        return RelationshipType::options();
    }
}
