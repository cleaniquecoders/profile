<?php

use CleaniqueCoders\Profile\Enums\RelationshipType;
use CleaniqueCoders\Profile\Models\EmergencyContact;
use Illuminate\Support\Facades\Schema;
use Workbench\App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('has an emergency_contacts table', function () {
    expect(Schema::hasTable('emergency_contacts'))->toBeTrue();
});

it('can create an emergency contact', function () {
    $contact = EmergencyContact::create([
        'contactable_id' => $this->user->id,
        'contactable_type' => get_class($this->user),
        'name' => 'Jane Doe',
        'relationship_type' => RelationshipType::SPOUSE,
        'phone' => '+1234567890',
        'email' => 'jane@example.com',
        'is_primary' => true,
        'notes' => 'Primary emergency contact',
    ]);

    expect($contact)->not->toBeNull()
        ->and($contact->name)->toBe('Jane Doe')
        ->and($contact->relationship_type)->toBe(RelationshipType::SPOUSE)
        ->and($contact->phone)->toBe('+1234567890')
        ->and($contact->is_primary)->toBeTrue();
});

it('emergency contact belongs to contactable model', function () {
    $contact = EmergencyContact::create([
        'contactable_id' => $this->user->id,
        'contactable_type' => get_class($this->user),
        'name' => 'John Doe',
        'relationship_type' => RelationshipType::FRIEND,
    ]);

    expect($contact->contactable)->toBeInstanceOf(User::class)
        ->and($contact->contactable->id)->toBe($this->user->id);
});

it('can filter emergency contacts by primary scope', function () {
    EmergencyContact::create([
        'contactable_id' => $this->user->id,
        'contactable_type' => get_class($this->user),
        'name' => 'Primary Contact',
        'relationship_type' => RelationshipType::SPOUSE,
        'is_primary' => true,
    ]);

    EmergencyContact::create([
        'contactable_id' => $this->user->id,
        'contactable_type' => get_class($this->user),
        'name' => 'Secondary Contact',
        'relationship_type' => RelationshipType::FRIEND,
        'is_primary' => false,
    ]);

    $primaryContacts = EmergencyContact::primary()->get();

    expect($primaryContacts)->toHaveCount(1)
        ->and($primaryContacts->first()->name)->toBe('Primary Contact');
});

it('can filter emergency contacts by relationship type scope', function () {
    EmergencyContact::create([
        'contactable_id' => $this->user->id,
        'contactable_type' => get_class($this->user),
        'name' => 'Spouse Contact',
        'relationship_type' => RelationshipType::SPOUSE,
    ]);

    EmergencyContact::create([
        'contactable_id' => $this->user->id,
        'contactable_type' => get_class($this->user),
        'name' => 'Friend Contact',
        'relationship_type' => RelationshipType::FRIEND,
    ]);

    $spouseContacts = EmergencyContact::relationship(RelationshipType::SPOUSE->value)->get();

    expect($spouseContacts)->toHaveCount(1)
        ->and($spouseContacts->first()->relationship_type)->toBe(RelationshipType::SPOUSE);
});

it('user can access emergency contacts via HasProfile trait', function () {
    EmergencyContact::create([
        'contactable_id' => $this->user->id,
        'contactable_type' => get_class($this->user),
        'name' => 'Contact 1',
        'relationship_type' => RelationshipType::SPOUSE,
    ]);

    EmergencyContact::create([
        'contactable_id' => $this->user->id,
        'contactable_type' => get_class($this->user),
        'name' => 'Contact 2',
        'relationship_type' => RelationshipType::FRIEND,
    ]);

    $contacts = $this->user->emergencyContacts;

    expect($contacts)->toHaveCount(2);
});

it('user can get primary emergency contact', function () {
    EmergencyContact::create([
        'contactable_id' => $this->user->id,
        'contactable_type' => get_class($this->user),
        'name' => 'Primary Contact',
        'relationship_type' => RelationshipType::SPOUSE,
        'is_primary' => true,
    ]);

    EmergencyContact::create([
        'contactable_id' => $this->user->id,
        'contactable_type' => get_class($this->user),
        'name' => 'Secondary Contact',
        'relationship_type' => RelationshipType::FRIEND,
        'is_primary' => false,
    ]);

    $primaryContact = $this->user->primaryEmergencyContact();

    expect($primaryContact)->not->toBeNull()
        ->and($primaryContact->name)->toBe('Primary Contact')
        ->and($primaryContact->is_primary)->toBeTrue();
});

it('user can get emergency contacts by relationship type', function () {
    EmergencyContact::create([
        'contactable_id' => $this->user->id,
        'contactable_type' => get_class($this->user),
        'name' => 'Mother Contact',
        'relationship_type' => RelationshipType::MOTHER,
    ]);

    EmergencyContact::create([
        'contactable_id' => $this->user->id,
        'contactable_type' => get_class($this->user),
        'name' => 'Father Contact',
        'relationship_type' => RelationshipType::FATHER,
    ]);

    EmergencyContact::create([
        'contactable_id' => $this->user->id,
        'contactable_type' => get_class($this->user),
        'name' => 'Friend Contact',
        'relationship_type' => RelationshipType::FRIEND,
    ]);

    $parentContacts = $this->user->getEmergencyContactsByRelationship(RelationshipType::MOTHER->value);

    expect($parentContacts)->toHaveCount(1)
        ->and($parentContacts->first()->name)->toBe('Mother Contact');
});

it('user can check if has emergency contacts', function () {
    expect($this->user->hasEmergencyContacts())->toBeFalse();

    EmergencyContact::create([
        'contactable_id' => $this->user->id,
        'contactable_type' => get_class($this->user),
        'name' => 'Emergency Contact',
        'relationship_type' => RelationshipType::SPOUSE,
    ]);

    expect($this->user->hasEmergencyContacts())->toBeTrue();
});
