<?php

use CleaniqueCoders\Profile\Models\Address;
use CleaniqueCoders\Profile\Models\Country;
use Illuminate\Support\Facades\Artisan;
use Workbench\App\Models\User;

beforeEach(function () {
    Artisan::call('profile:seed');
    $this->user = User::factory()->create();
    $this->country = Country::first();
});

it('can set coordinates for address', function () {
    $address = Address::create([
        'addressable_id' => $this->user->id,
        'addressable_type' => get_class($this->user),
        'country_id' => $this->country->id,
        'primary' => '123 Main St',
        'city' => 'Kuala Lumpur',
        'state' => 'Federal Territory',
        'postcode' => '50000',
    ]);

    $address->setCoordinates(3.139003, 101.686855);

    expect($address->latitude)->toBe('3.13900300')
        ->and($address->longitude)->toBe('101.68685500')
        ->and($address->hasCoordinates())->toBeTrue();
});

it('can mark address as valid', function () {
    $address = Address::create([
        'addressable_id' => $this->user->id,
        'addressable_type' => get_class($this->user),
        'country_id' => $this->country->id,
        'primary' => '123 Main St',
        'city' => 'Kuala Lumpur',
        'state' => 'Federal Territory',
        'postcode' => '50000',
    ]);

    $address->markAsValid();

    expect($address->validation_status)->toBe(Address::STATUS_VALID)
        ->and($address->validated_at)->not->toBeNull()
        ->and($address->isValid())->toBeTrue();
});

it('can mark address as invalid', function () {
    $address = Address::create([
        'addressable_id' => $this->user->id,
        'addressable_type' => get_class($this->user),
        'country_id' => $this->country->id,
        'primary' => '123 Main St',
    ]);

    $address->markAsInvalid();

    expect($address->validation_status)->toBe(Address::STATUS_INVALID)
        ->and($address->validated_at)->not->toBeNull()
        ->and($address->isInvalid())->toBeTrue();
});

it('can mark address as partially valid', function () {
    $address = Address::create([
        'addressable_id' => $this->user->id,
        'addressable_type' => get_class($this->user),
        'country_id' => $this->country->id,
        'primary' => '123 Main St',
        'city' => 'Kuala Lumpur',
    ]);

    $address->markAsPartial();

    expect($address->validation_status)->toBe(Address::STATUS_PARTIAL)
        ->and($address->validated_at)->not->toBeNull();
});

it('can reset validation status', function () {
    $address = Address::create([
        'addressable_id' => $this->user->id,
        'addressable_type' => get_class($this->user),
        'country_id' => $this->country->id,
        'primary' => '123 Main St',
    ]);

    $address->markAsValid();
    $address->resetValidation();

    expect($address->validation_status)->toBe(Address::STATUS_PENDING)
        ->and($address->validated_at)->toBeNull()
        ->and($address->isPending())->toBeTrue();
});

it('can check if address has coordinates', function () {
    $address = Address::create([
        'addressable_id' => $this->user->id,
        'addressable_type' => get_class($this->user),
        'country_id' => $this->country->id,
        'primary' => '123 Main St',
    ]);

    expect($address->hasCoordinates())->toBeFalse();

    $address->setCoordinates(3.139003, 101.686855);

    expect($address->hasCoordinates())->toBeTrue();
});

it('can get full address as string', function () {
    $address = Address::create([
        'addressable_id' => $this->user->id,
        'addressable_type' => get_class($this->user),
        'country_id' => $this->country->id,
        'primary' => '123 Main St',
        'secondary' => 'Apt 4B',
        'city' => 'Kuala Lumpur',
        'state' => 'Federal Territory',
        'postcode' => '50000',
    ]);

    $fullAddress = $address->getFullAddress();

    expect($fullAddress)->toContain('123 Main St')
        ->and($fullAddress)->toContain('Apt 4B')
        ->and($fullAddress)->toContain('Kuala Lumpur')
        ->and($fullAddress)->toContain('50000');
});

it('can filter validated addresses', function () {
    $validatedAddress = Address::create([
        'addressable_id' => $this->user->id,
        'addressable_type' => get_class($this->user),
        'country_id' => $this->country->id,
        'primary' => '123 Main St',
        'city' => 'Kuala Lumpur',
    ]);
    $validatedAddress->markAsValid();

    $pendingAddress = Address::create([
        'addressable_id' => $this->user->id,
        'addressable_type' => get_class($this->user),
        'country_id' => $this->country->id,
        'primary' => '456 Second St',
    ]);

    $validated = Address::validated()->get();
    $pending = Address::pending()->get();

    expect($validated)->toHaveCount(1)
        ->and($validated->first()->id)->toBe($validatedAddress->id)
        ->and($pending)->toHaveCount(1)
        ->and($pending->first()->id)->toBe($pendingAddress->id);
});

it('can filter valid addresses', function () {
    $validAddress = Address::create([
        'addressable_id' => $this->user->id,
        'addressable_type' => get_class($this->user),
        'country_id' => $this->country->id,
        'primary' => '123 Main St',
    ]);
    $validAddress->markAsValid();

    $invalidAddress = Address::create([
        'addressable_id' => $this->user->id,
        'addressable_type' => get_class($this->user),
        'country_id' => $this->country->id,
        'primary' => '456 Second St',
    ]);
    $invalidAddress->markAsInvalid();

    $valid = Address::valid()->get();
    $invalid = Address::invalid()->get();

    expect($valid)->toHaveCount(1)
        ->and($valid->first()->id)->toBe($validAddress->id)
        ->and($invalid)->toHaveCount(1)
        ->and($invalid->first()->id)->toBe($invalidAddress->id);
});

it('can filter addresses with coordinates', function () {
    $addressWithCoords = Address::create([
        'addressable_id' => $this->user->id,
        'addressable_type' => get_class($this->user),
        'country_id' => $this->country->id,
        'primary' => '123 Main St',
        'latitude' => 3.139003,
        'longitude' => 101.686855,
    ]);

    $addressWithoutCoords = Address::create([
        'addressable_id' => $this->user->id,
        'addressable_type' => get_class($this->user),
        'country_id' => $this->country->id,
        'primary' => '456 Second St',
    ]);

    $withCoordinates = Address::withCoordinates()->get();

    expect($withCoordinates)->toHaveCount(1)
        ->and($withCoordinates->first()->id)->toBe($addressWithCoords->id);
});

it('has default validation status as pending', function () {
    $address = Address::create([
        'addressable_id' => $this->user->id,
        'addressable_type' => get_class($this->user),
        'country_id' => $this->country->id,
        'primary' => '123 Main St',
    ]);

    expect($address->validation_status)->toBe(Address::STATUS_PENDING)
        ->and($address->isPending())->toBeTrue();
});
