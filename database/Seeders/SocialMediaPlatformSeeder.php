<?php

namespace CleaniqueCoders\Profile\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SocialMediaPlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $platforms = [
            'facebook' => 'Facebook',
            'twitter' => 'Twitter / X',
            'instagram' => 'Instagram',
            'linkedin' => 'LinkedIn',
            'github' => 'GitHub',
            'gitlab' => 'GitLab',
            'youtube' => 'YouTube',
            'tiktok' => 'TikTok',
            'pinterest' => 'Pinterest',
            'snapchat' => 'Snapchat',
            'reddit' => 'Reddit',
            'telegram' => 'Telegram',
            'whatsapp' => 'WhatsApp',
            'discord' => 'Discord',
            'slack' => 'Slack',
            'medium' => 'Medium',
            'behance' => 'Behance',
            'dribbble' => 'Dribbble',
            'stackoverflow' => 'Stack Overflow',
            'twitch' => 'Twitch',
        ];

        // This seeder is for reference purposes
        // The actual platform values are stored directly in the social_media table
        // You can use this data to create a platforms reference table if needed
    }

    /**
     * Get all available platforms.
     */
    public static function getPlatforms(): array
    {
        return [
            'facebook' => 'Facebook',
            'twitter' => 'Twitter / X',
            'instagram' => 'Instagram',
            'linkedin' => 'LinkedIn',
            'github' => 'GitHub',
            'gitlab' => 'GitLab',
            'youtube' => 'YouTube',
            'tiktok' => 'TikTok',
            'pinterest' => 'Pinterest',
            'snapchat' => 'Snapchat',
            'reddit' => 'Reddit',
            'telegram' => 'Telegram',
            'whatsapp' => 'WhatsApp',
            'discord' => 'Discord',
            'slack' => 'Slack',
            'medium' => 'Medium',
            'behance' => 'Behance',
            'dribbble' => 'Dribbble',
            'stackoverflow' => 'Stack Overflow',
            'twitch' => 'Twitch',
        ];
    }
}
