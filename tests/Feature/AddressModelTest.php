<?php

use CleaniqueCoders\Profile\Models\Address;
use CleaniqueCoders\Profile\Models\Country;
use Illuminate\Support\Facades\Artisan;
use Workbench\App\Models\User;

beforeEach(function () {
    Artisan::call('profile:seed');
    $this->user = User::factory()->create();
    $this->country = Country::where('code', 'MY')->first();
});

it('can create an address with all fields', function () {
    $address = Address::create([
        'addressable_id' => $this->user->id,
        'addressable_type' => get_class($this->user),
        'country_id' => $this->country->id,
        'primary' => '123 Main Street',
        'secondary' => 'Apt 4B',
        'city' => 'Kuala Lumpur',
        'state' => 'Federal Territory',
        'postcode' => '50000',
    ]);

    expect($address)->not->toBeNull()
        ->and($address->primary)->toBe('123 Main Street')
        ->and($address->city)->toBe('Kuala Lumpur');
});

it('address belongs to addressable model', function () {
    $address = Address::create([
        'addressable_id' => $this->user->id,
        'addressable_type' => get_class($this->user),
        'country_id' => $this->country->id,
        'address_1' => '456 Another St',
        'city' => 'Penang',
        'postcode' => '10000',
    ]);

    expect($address->addressable)->toBeInstanceOf(User::class)
        ->and($address->addressable->id)->toBe($this->user->id);
});

it('address belongs to country', function () {
    $address = Address::create([
        'addressable_id' => $this->user->id,
        'addressable_type' => get_class($this->user),
        'country_id' => $this->country->id,
        'address_1' => '789 Test Road',
        'city' => 'Johor Bahru',
        'postcode' => '80000',
    ]);

    expect($address->country)->toBeInstanceOf(Country::class)
        ->and($address->country->id)->toBe($this->country->id)
        ->and($address->country->code)->toBe('MY');
});

it('can create multiple addresses for same user', function () {
    Address::create([
        'addressable_id' => $this->user->id,
        'addressable_type' => get_class($this->user),
        'country_id' => $this->country->id,
        'address_1' => 'Address 1',
        'city' => 'KL',
        'postcode' => '50000',
    ]);

    Address::create([
        'addressable_id' => $this->user->id,
        'addressable_type' => get_class($this->user),
        'country_id' => $this->country->id,
        'address_1' => 'Address 2',
        'city' => 'Penang',
        'postcode' => '10000',
    ]);

    $addresses = Address::where('addressable_id', $this->user->id)->get();

    expect($addresses)->toHaveCount(2);
});

it('can access country details via relationship', function () {
    $address = Address::create([
        'addressable_id' => $this->user->id,
        'addressable_type' => get_class($this->user),
        'country_id' => $this->country->id,
        'address_1' => '100 Test St',
        'city' => 'Kuala Lumpur',
        'postcode' => '50000',
    ]);

    expect($address->country->name)->toBe('Malaysia')
        ->and($address->country->code)->toBe('MY');
});
