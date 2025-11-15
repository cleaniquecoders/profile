<?php

namespace CleaniqueCoders\Profile\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * EmergencyContactable Trait.
 */
trait EmergencyContactable
{
    /**
     * Get all emergency contacts.
     */
    public function emergencyContacts(): MorphMany
    {
        return $this->morphMany(
            config('profile.providers.emergency_contact.model'),
            config('profile.providers.emergency_contact.type')
        );
    }

    /**
     * Get primary emergency contact.
     */
    public function primaryEmergencyContact()
    {
        return $this->emergencyContacts()->where('is_primary', true)->first();
    }

    /**
     * Get emergency contacts by relationship type.
     */
    public function getEmergencyContactsByRelationship(string $relationshipType)
    {
        return $this->emergencyContacts()->where('relationship_type', $relationshipType)->get();
    }

    /**
     * Check if has emergency contacts.
     */
    public function hasEmergencyContacts(): bool
    {
        return $this->emergencyContacts()->exists();
    }
}
