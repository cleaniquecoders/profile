<?php

namespace CleaniqueCoders\Profile\Enums;

use CleaniqueCoders\Traitify\Concerns\InteractsWithEnum;

enum SocialMediaPlatform: string
{
    use InteractsWithEnum;

    case FACEBOOK = 'facebook';
    case TWITTER = 'twitter';
    case INSTAGRAM = 'instagram';
    case LINKEDIN = 'linkedin';
    case GITHUB = 'github';
    case GITLAB = 'gitlab';
    case YOUTUBE = 'youtube';
    case TIKTOK = 'tiktok';
    case PINTEREST = 'pinterest';
    case SNAPCHAT = 'snapchat';
    case REDDIT = 'reddit';
    case TELEGRAM = 'telegram';
    case WHATSAPP = 'whatsapp';
    case DISCORD = 'discord';
    case SLACK = 'slack';
    case MEDIUM = 'medium';
    case BEHANCE = 'behance';
    case DRIBBBLE = 'dribbble';
    case STACKOVERFLOW = 'stackoverflow';
    case TWITCH = 'twitch';

    /**
     * Get the label for the platform.
     */
    public function label(): string
    {
        return match ($this) {
            self::FACEBOOK => 'Facebook',
            self::TWITTER => 'Twitter / X',
            self::INSTAGRAM => 'Instagram',
            self::LINKEDIN => 'LinkedIn',
            self::GITHUB => 'GitHub',
            self::GITLAB => 'GitLab',
            self::YOUTUBE => 'YouTube',
            self::TIKTOK => 'TikTok',
            self::PINTEREST => 'Pinterest',
            self::SNAPCHAT => 'Snapchat',
            self::REDDIT => 'Reddit',
            self::TELEGRAM => 'Telegram',
            self::WHATSAPP => 'WhatsApp',
            self::DISCORD => 'Discord',
            self::SLACK => 'Slack',
            self::MEDIUM => 'Medium',
            self::BEHANCE => 'Behance',
            self::DRIBBBLE => 'Dribbble',
            self::STACKOVERFLOW => 'Stack Overflow',
            self::TWITCH => 'Twitch',
        };
    }

    /**
     * Get the description for the platform.
     */
    public function description(): string
    {
        return match ($this) {
            self::FACEBOOK => 'Facebook social network',
            self::TWITTER => 'Twitter (X) microblogging platform',
            self::INSTAGRAM => 'Instagram photo sharing',
            self::LINKEDIN => 'LinkedIn professional network',
            self::GITHUB => 'GitHub code repository',
            self::GITLAB => 'GitLab code repository',
            self::YOUTUBE => 'YouTube video platform',
            self::TIKTOK => 'TikTok short video platform',
            self::PINTEREST => 'Pinterest visual discovery',
            self::SNAPCHAT => 'Snapchat messaging app',
            self::REDDIT => 'Reddit discussion platform',
            self::TELEGRAM => 'Telegram messaging app',
            self::WHATSAPP => 'WhatsApp messaging app',
            self::DISCORD => 'Discord chat platform',
            self::SLACK => 'Slack team communication',
            self::MEDIUM => 'Medium blogging platform',
            self::BEHANCE => 'Behance creative portfolio',
            self::DRIBBBLE => 'Dribbble design showcase',
            self::STACKOVERFLOW => 'Stack Overflow Q&A platform',
            self::TWITCH => 'Twitch streaming platform',
        };
    }

    /**
     * Get the base URL pattern for the platform.
     */
    public function urlPattern(): string
    {
        return match ($this) {
            self::FACEBOOK => 'https://facebook.com/{username}',
            self::TWITTER => 'https://twitter.com/{username}',
            self::INSTAGRAM => 'https://instagram.com/{username}',
            self::LINKEDIN => 'https://linkedin.com/in/{username}',
            self::GITHUB => 'https://github.com/{username}',
            self::GITLAB => 'https://gitlab.com/{username}',
            self::YOUTUBE => 'https://youtube.com/@{username}',
            self::TIKTOK => 'https://tiktok.com/@{username}',
            self::PINTEREST => 'https://pinterest.com/{username}',
            self::SNAPCHAT => 'https://snapchat.com/add/{username}',
            self::REDDIT => 'https://reddit.com/u/{username}',
            self::TELEGRAM => 'https://t.me/{username}',
            self::WHATSAPP => 'https://wa.me/{username}',
            self::DISCORD => '{username}',
            self::SLACK => '{username}',
            self::MEDIUM => 'https://medium.com/@{username}',
            self::BEHANCE => 'https://behance.net/{username}',
            self::DRIBBBLE => 'https://dribbble.com/{username}',
            self::STACKOVERFLOW => 'https://stackoverflow.com/users/{username}',
            self::TWITCH => 'https://twitch.tv/{username}',
        };
    }
}
