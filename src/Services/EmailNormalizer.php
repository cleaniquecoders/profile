<?php

namespace CleaniqueCoders\Profile\Services;

class EmailNormalizer
{
    /**
     * Normalize email address.
     * - Convert to lowercase
     * - Remove dots in Gmail addresses (before @)
     * - Remove plus addressing
     * - Trim whitespace
     */
    public static function normalize(string $email, bool $removeDots = false, bool $removePlusAddressing = false): string
    {
        // Trim and convert to lowercase
        $email = trim(strtolower($email));

        // Split email into local and domain parts
        $parts = explode('@', $email);

        if (count($parts) !== 2) {
            return $email; // Invalid email format, return as is
        }

        [$local, $domain] = $parts;

        // Remove plus addressing if enabled (e.g., user+tag@example.com -> user@example.com)
        if ($removePlusAddressing && str_contains($local, '+')) {
            $local = substr($local, 0, strpos($local, '+'));
        }

        // Handle Gmail-specific normalization
        if (self::isGmailDomain($domain)) {
            // Remove dots from Gmail addresses (they're ignored by Gmail)
            if ($removeDots) {
                $local = str_replace('.', '', $local);
            }

            // Gmail ignores everything after + in the local part
            if (str_contains($local, '+')) {
                $local = substr($local, 0, strpos($local, '+'));
            }
        }

        return $local.'@'.$domain;
    }

    /**
     * Get the canonical form of an email (most normalized version).
     */
    public static function canonical(string $email): string
    {
        return self::normalize($email, removeDots: true, removePlusAddressing: true);
    }

    /**
     * Check if the domain is a Gmail domain.
     */
    private static function isGmailDomain(string $domain): bool
    {
        $gmailDomains = ['gmail.com', 'googlemail.com'];

        return in_array(strtolower($domain), $gmailDomains);
    }

    /**
     * Get the domain from an email address.
     */
    public static function getDomain(string $email): ?string
    {
        $parts = explode('@', $email);

        return count($parts) === 2 ? strtolower($parts[1]) : null;
    }

    /**
     * Get the local part from an email address.
     */
    public static function getLocalPart(string $email): ?string
    {
        $parts = explode('@', $email);

        return count($parts) === 2 ? strtolower($parts[0]) : null;
    }

    /**
     * Check if two email addresses are equivalent.
     */
    public static function areEquivalent(string $email1, string $email2): bool
    {
        return self::canonical($email1) === self::canonical($email2);
    }

    /**
     * Validate email format.
     */
    public static function isValid(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Check if email is from a disposable email service.
     */
    public static function isDisposable(string $email): bool
    {
        $domain = self::getDomain($email);

        if (! $domain) {
            return false;
        }

        // Common disposable email domains
        $disposableDomains = [
            'mailinator.com',
            'guerrillamail.com',
            'tempmail.com',
            'throwaway.email',
            '10minutemail.com',
            'sharklasers.com',
            'guerrillamailblock.com',
            'spam4.me',
            'maildrop.cc',
            'yopmail.com',
            'trashmail.com',
        ];

        return in_array($domain, $disposableDomains);
    }

    /**
     * Check if email is from a business/corporate domain.
     */
    public static function isBusinessEmail(string $email): bool
    {
        $domain = self::getDomain($email);

        if (! $domain) {
            return false;
        }

        // Common free email providers
        $freeProviders = [
            'gmail.com',
            'googlemail.com',
            'yahoo.com',
            'yahoo.co.uk',
            'hotmail.com',
            'outlook.com',
            'live.com',
            'msn.com',
            'aol.com',
            'icloud.com',
            'mail.com',
            'protonmail.com',
            'zoho.com',
        ];

        return ! in_array($domain, $freeProviders);
    }

    /**
     * Get email provider name.
     */
    public static function getProvider(string $email): ?string
    {
        $domain = self::getDomain($email);

        if (! $domain) {
            return null;
        }

        $providers = [
            'gmail.com' => 'Gmail',
            'googlemail.com' => 'Gmail',
            'yahoo.com' => 'Yahoo',
            'yahoo.co.uk' => 'Yahoo',
            'hotmail.com' => 'Hotmail',
            'outlook.com' => 'Outlook',
            'live.com' => 'Microsoft',
            'msn.com' => 'MSN',
            'aol.com' => 'AOL',
            'icloud.com' => 'iCloud',
            'me.com' => 'iCloud',
            'mac.com' => 'iCloud',
            'protonmail.com' => 'ProtonMail',
            'zoho.com' => 'Zoho',
        ];

        return $providers[$domain] ?? 'Other';
    }

    /**
     * Suggest corrections for common email typos.
     */
    public static function suggestCorrection(string $email): ?string
    {
        $domain = self::getDomain($email);

        if (! $domain) {
            return null;
        }

        // Common domain typos
        $corrections = [
            'gmial.com' => 'gmail.com',
            'gmai.com' => 'gmail.com',
            'gmil.com' => 'gmail.com',
            'yahooo.com' => 'yahoo.com',
            'yaho.com' => 'yahoo.com',
            'hotmial.com' => 'hotmail.com',
            'hotmai.com' => 'hotmail.com',
            'outloo.com' => 'outlook.com',
            'outlok.com' => 'outlook.com',
        ];

        if (isset($corrections[$domain])) {
            $local = self::getLocalPart($email);

            return $local.'@'.$corrections[$domain];
        }

        return null;
    }
}
