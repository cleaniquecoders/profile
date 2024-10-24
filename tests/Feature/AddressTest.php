<?php

use CleaniqueCoders\Profile\Models\Address;
use Illuminate\Support\Facades\Schema;
use Workbench\App\Models\User;

beforeEach(function () {
    // Set up any necessary test dependencies (e.g., user model).
    $this->user = User::factory()->create();
});

it('has addresses table', function () {
    expect(Schema::hasTable('addresses'))->toBeTrue();
});

it('has no address records', function () {
    expect(Address::count())->toBe(0);
});

it('can create an address', function () {
    $address = $this->user->addresses()->create([
        'primary' => 'OSTIA, Bangi',
        'city' => 'Bandar Baru Bangi',
        'state' => 'Selangor',
        'country_id' => 131,
        'is_default' => true,
    ]);

    expect($address)->not->toBeNull()
        ->and($address->primary)->toBe('OSTIA, Bangi')
        ->and($address->city)->toBe('Bandar Baru Bangi')
        ->and($address->state)->toBe('Selangor')
        ->and($address->country_id)->toBe(131)
        ->and($address->is_default)->toBeTrue();
});
