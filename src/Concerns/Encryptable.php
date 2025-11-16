<?php

namespace CleaniqueCoders\Profile\Concerns;

use Illuminate\Support\Facades\Crypt;

trait Encryptable
{
    /**
     * Boot the trait.
     */
    public static function bootEncryptable(): void
    {
        // Encrypt specified attributes before saving
        static::saving(function ($model) {
            if (property_exists($model, 'encryptable')) {
                foreach ($model->encryptable as $attribute) {
                    if (isset($model->attributes[$attribute]) && ! empty($model->attributes[$attribute])) {
                        // Only encrypt if not already encrypted
                        if (! $model->isEncrypted($attribute)) {
                            $model->attributes[$attribute] = Crypt::encryptString($model->attributes[$attribute]);
                        }
                    }
                }
            }
        });
    }

    /**
     * Get a decrypted attribute value.
     */
    public function getAttributeValue($key)
    {
        $value = parent::getAttributeValue($key);

        // Decrypt if this is an encryptable attribute
        if ($this->isEncryptableAttribute($key) && ! is_null($value)) {
            try {
                return Crypt::decryptString($value);
            } catch (\Exception $e) {
                // If decryption fails, return the original value
                return $value;
            }
        }

        return $value;
    }

    /**
     * Set an attribute value (will be encrypted on save).
     */
    public function setAttribute($key, $value)
    {
        // Store the raw value; encryption happens in the saving event
        return parent::setAttribute($key, $value);
    }

    /**
     * Check if an attribute is encryptable.
     */
    protected function isEncryptableAttribute(string $attribute): bool
    {
        return property_exists($this, 'encryptable') && in_array($attribute, $this->encryptable);
    }

    /**
     * Check if a value is already encrypted.
     */
    protected function isEncrypted(string $attribute): bool
    {
        if (! isset($this->attributes[$attribute])) {
            return false;
        }

        try {
            Crypt::decryptString($this->attributes[$attribute]);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Manually encrypt an attribute.
     */
    public function encryptAttribute(string $attribute): void
    {
        if (isset($this->attributes[$attribute]) && ! $this->isEncrypted($attribute)) {
            $this->attributes[$attribute] = Crypt::encryptString($this->attributes[$attribute]);
        }
    }

    /**
     * Manually decrypt an attribute.
     */
    public function decryptAttribute(string $attribute): ?string
    {
        if (! isset($this->attributes[$attribute])) {
            return null;
        }

        try {
            return Crypt::decryptString($this->attributes[$attribute]);
        } catch (\Exception $e) {
            return $this->attributes[$attribute];
        }
    }

    /**
     * Get all encryptable attributes.
     */
    public function getEncryptableAttributes(): array
    {
        return property_exists($this, 'encryptable') ? $this->encryptable : [];
    }
}
