<?php

namespace CleaniqueCoders\Profile\Services;

use Illuminate\Support\Facades\Crypt;

class EncryptionManager
{
    /**
     * Encrypt a value.
     */
    public static function encrypt(string $value): string
    {
        return Crypt::encryptString($value);
    }

    /**
     * Decrypt a value.
     */
    public static function decrypt(string $value): string
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value;
        }
    }

    /**
     * Check if a value is encrypted.
     */
    public static function isEncrypted(?string $value): bool
    {
        if (empty($value)) {
            return false;
        }

        try {
            Crypt::decryptString($value);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Encrypt all emails for a model.
     */
    public static function encryptEmails(mixed $model): int
    {
        if (! method_exists($model, 'emails')) {
            return 0;
        }

        $count = 0;

        foreach ($model->emails as $email) {
            if (! self::isEncrypted($email->email)) {
                $email->email = self::encrypt($email->email);
                $email->saveQuietly(); // Save without triggering events
                $count++;
            }
        }

        return $count;
    }

    /**
     * Decrypt all emails for a model.
     */
    public static function decryptEmails(mixed $model): int
    {
        if (! method_exists($model, 'emails')) {
            return 0;
        }

        $count = 0;

        foreach ($model->emails as $email) {
            if (self::isEncrypted($email->email)) {
                $email->email = self::decrypt($email->email);
                $email->saveQuietly(); // Save without triggering events
                $count++;
            }
        }

        return $count;
    }

    /**
     * Encrypt all phones for a model.
     */
    public static function encryptPhones(mixed $model): int
    {
        if (! method_exists($model, 'phones')) {
            return 0;
        }

        $count = 0;

        foreach ($model->phones as $phone) {
            if (! empty($phone->number) && ! self::isEncrypted($phone->number)) {
                $phone->number = self::encrypt($phone->number);
                $phone->saveQuietly(); // Save without triggering events
                $count++;
            }
        }

        return $count;
    }

    /**
     * Decrypt all phones for a model.
     */
    public static function decryptPhones(mixed $model): int
    {
        if (! method_exists($model, 'phones')) {
            return 0;
        }

        $count = 0;

        foreach ($model->phones as $phone) {
            if (! empty($phone->number) && self::isEncrypted($phone->number)) {
                $phone->number = self::decrypt($phone->number);
                $phone->saveQuietly(); // Save without triggering events
                $count++;
            }
        }

        return $count;
    }

    /**
     * Encrypt all addresses for a model.
     */
    public static function encryptAddresses(mixed $model): int
    {
        if (! method_exists($model, 'addresses')) {
            return 0;
        }

        $count = 0;

        foreach ($model->addresses as $address) {
            $encrypted = false;

            foreach (['primary', 'secondary', 'city', 'state', 'postcode'] as $field) {
                if ($address->$field && ! self::isEncrypted($address->$field)) {
                    $address->$field = self::encrypt($address->$field);
                    $encrypted = true;
                }
            }

            if ($encrypted) {
                $address->saveQuietly(); // Save without triggering events
                $count++;
            }
        }

        return $count;
    }

    /**
     * Decrypt all addresses for a model.
     */
    public static function decryptAddresses(mixed $model): int
    {
        if (! method_exists($model, 'addresses')) {
            return 0;
        }

        $count = 0;

        foreach ($model->addresses as $address) {
            $decrypted = false;

            foreach (['primary', 'secondary', 'city', 'state', 'postcode'] as $field) {
                if ($address->$field && self::isEncrypted($address->$field)) {
                    $address->$field = self::decrypt($address->$field);
                    $decrypted = true;
                }
            }

            if ($decrypted) {
                $address->saveQuietly(); // Save without triggering events
                $count++;
            }
        }

        return $count;
    }

    /**
     * Encrypt all profile data for a model.
     */
    public static function encryptAll(mixed $model): array
    {
        return [
            'emails' => self::encryptEmails($model),
            'phones' => self::encryptPhones($model),
            'addresses' => self::encryptAddresses($model),
        ];
    }

    /**
     * Decrypt all profile data for a model.
     */
    public static function decryptAll(mixed $model): array
    {
        return [
            'emails' => self::decryptEmails($model),
            'phones' => self::decryptPhones($model),
            'addresses' => self::decryptAddresses($model),
        ];
    }

    /**
     * Hash a value (one-way encryption for comparison).
     */
    public static function hash(string $value): string
    {
        return hash('sha256', $value);
    }

    /**
     * Verify a value against a hash.
     */
    public static function verifyHash(string $value, string $hash): bool
    {
        return hash('sha256', $value) === $hash;
    }
}
