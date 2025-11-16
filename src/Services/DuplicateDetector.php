<?php

namespace CleaniqueCoders\Profile\Services;

use CleaniqueCoders\Profile\Models\Address;
use CleaniqueCoders\Profile\Models\Email;
use CleaniqueCoders\Profile\Models\Phone;
use Illuminate\Support\Collection;

class DuplicateDetector
{
    /**
     * Find duplicate emails based on canonical form.
     */
    public static function findDuplicateEmails(Email $email): Collection
    {
        $canonical = $email->getCanonical();

        return Email::query()
            ->where('id', '!=', $email->id)
            ->get()
            ->filter(function ($other) use ($canonical) {
                return EmailNormalizer::canonical($other->email) === $canonical;
            });
    }

    /**
     * Find duplicate phones based on E.164 format.
     */
    public static function findDuplicatePhones(Phone $phone, ?string $countryCode = null): Collection
    {
        $e164 = $phone->toE164($countryCode);

        return Phone::query()
            ->where('id', '!=', $phone->id)
            ->get()
            ->filter(function ($other) use ($e164, $countryCode) {
                return $other->toE164($countryCode) === $e164;
            });
    }

    /**
     * Find duplicate addresses based on similarity.
     */
    public static function findDuplicateAddresses(Address $address, float $threshold = 0.8): Collection
    {
        $candidates = Address::query()
            ->where('id', '!=', $address->id)
            ->when($address->country_id, function ($query) use ($address) {
                $query->where('country_id', $address->country_id);
            })
            ->get();

        return $candidates->filter(function ($other) use ($address, $threshold) {
            return self::calculateAddressSimilarity($address, $other) >= $threshold;
        });
    }

    /**
     * Calculate similarity between two addresses (0-1 scale).
     */
    public static function calculateAddressSimilarity(Address $address1, Address $address2): float
    {
        $scores = [];

        // Compare primary address
        if ($address1->primary && $address2->primary) {
            $scores[] = self::stringSimilarity($address1->primary, $address2->primary);
        }

        // Compare city
        if ($address1->city && $address2->city) {
            $scores[] = self::stringSimilarity($address1->city, $address2->city);
        }

        // Compare state
        if ($address1->state && $address2->state) {
            $scores[] = self::stringSimilarity($address1->state, $address2->state);
        }

        // Compare postcode (exact match or fail)
        if ($address1->postcode && $address2->postcode) {
            $scores[] = strtolower($address1->postcode) === strtolower($address2->postcode) ? 1.0 : 0.0;
        }

        // Compare country (exact match or fail)
        if ($address1->country_id && $address2->country_id) {
            $scores[] = $address1->country_id === $address2->country_id ? 1.0 : 0.0;
        }

        return count($scores) > 0 ? array_sum($scores) / count($scores) : 0.0;
    }

    /**
     * Calculate string similarity (0-1 scale).
     */
    private static function stringSimilarity(string $str1, string $str2): float
    {
        $str1 = strtolower(trim($str1));
        $str2 = strtolower(trim($str2));

        if ($str1 === $str2) {
            return 1.0;
        }

        // Use Levenshtein distance
        $maxLen = max(strlen($str1), strlen($str2));

        if ($maxLen === 0) {
            return 1.0;
        }

        $distance = levenshtein($str1, $str2);

        return 1 - ($distance / $maxLen);
    }

    /**
     * Merge duplicate emails (keep the first one, transfer relationships).
     */
    public static function mergeEmails(Email $primary, Email $duplicate): Email
    {
        // If duplicate is verified and primary is not, transfer verification
        if ($duplicate->isVerified() && $primary->isUnverified()) {
            $primary->verified_at = $duplicate->verified_at;
            $primary->save();
        }

        // Update the duplicate to mark it as merged
        $duplicate->delete();

        return $primary;
    }

    /**
     * Merge duplicate phones (keep the first one, transfer relationships).
     */
    public static function mergePhones(Phone $primary, Phone $duplicate): Phone
    {
        // If duplicate is verified and primary is not, transfer verification
        if ($duplicate->isVerified() && $primary->isUnverified()) {
            $primary->verified_at = $duplicate->verified_at;
            $primary->save();
        }

        // Update the duplicate to mark it as merged
        $duplicate->delete();

        return $primary;
    }

    /**
     * Merge duplicate addresses (keep the first one, transfer data).
     */
    public static function mergeAddresses(Address $primary, Address $duplicate): Address
    {
        // Transfer coordinates if primary doesn't have them
        if (! $primary->hasCoordinates() && $duplicate->hasCoordinates()) {
            $primary->latitude = $duplicate->latitude;
            $primary->longitude = $duplicate->longitude;
        }

        // Transfer validation status if primary is not validated
        if ($primary->isPending() && ! $duplicate->isPending()) {
            $primary->validation_status = $duplicate->validation_status;
            $primary->validated_at = $duplicate->validated_at;
        }

        $primary->save();

        // Delete the duplicate
        $duplicate->delete();

        return $primary;
    }

    /**
     * Find all duplicates for a profileable model.
     */
    public static function findAllDuplicates(mixed $model): array
    {
        $duplicates = [
            'emails' => [],
            'phones' => [],
            'addresses' => [],
        ];

        // Find duplicate emails
        if (method_exists($model, 'emails')) {
            foreach ($model->emails as $email) {
                $dups = self::findDuplicateEmails($email);
                if ($dups->isNotEmpty()) {
                    $duplicates['emails'][$email->id] = $dups;
                }
            }
        }

        // Find duplicate phones
        if (method_exists($model, 'phones')) {
            foreach ($model->phones as $phone) {
                $dups = self::findDuplicatePhones($phone);
                if ($dups->isNotEmpty()) {
                    $duplicates['phones'][$phone->id] = $dups;
                }
            }
        }

        // Find duplicate addresses
        if (method_exists($model, 'addresses')) {
            foreach ($model->addresses as $address) {
                $dups = self::findDuplicateAddresses($address);
                if ($dups->isNotEmpty()) {
                    $duplicates['addresses'][$address->id] = $dups;
                }
            }
        }

        return $duplicates;
    }

    /**
     * Auto-merge all duplicates for a model.
     */
    public static function autoMergeDuplicates(mixed $model): array
    {
        $merged = [
            'emails' => 0,
            'phones' => 0,
            'addresses' => 0,
        ];

        $duplicates = self::findAllDuplicates($model);

        // Merge emails
        foreach ($duplicates['emails'] as $primaryId => $dups) {
            $primary = Email::find($primaryId);
            if ($primary) {
                foreach ($dups as $duplicate) {
                    self::mergeEmails($primary, $duplicate);
                    $merged['emails']++;
                }
            }
        }

        // Merge phones
        foreach ($duplicates['phones'] as $primaryId => $dups) {
            $primary = Phone::find($primaryId);
            if ($primary) {
                foreach ($dups as $duplicate) {
                    self::mergePhones($primary, $duplicate);
                    $merged['phones']++;
                }
            }
        }

        // Merge addresses
        foreach ($duplicates['addresses'] as $primaryId => $dups) {
            $primary = Address::find($primaryId);
            if ($primary) {
                foreach ($dups as $duplicate) {
                    self::mergeAddresses($primary, $duplicate);
                    $merged['addresses']++;
                }
            }
        }

        return $merged;
    }
}
