<?php

namespace CleaniqueCoders\Profile\Database\Seeders;

use CleaniqueCoders\Profile\Enums\SocialMediaPlatform;
use Illuminate\Database\Seeder;

class SocialMediaPlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // This seeder is for reference purposes
        // The actual platform values are stored directly in the social_media table
        // Platform values are defined in the SocialMediaPlatform enum
    }

    /**
     * Get all available platforms.
     */
    public static function getPlatforms(): array
    {
        return SocialMediaPlatform::values();
    }

    /**
     * Get platform labels.
     */
    public static function getPlatformLabels(): array
    {
        return SocialMediaPlatform::labels();
    }

    /**
     * Get platform options for select inputs.
     */
    public static function getPlatformOptions(): array
    {
        return SocialMediaPlatform::options();
    }
}
