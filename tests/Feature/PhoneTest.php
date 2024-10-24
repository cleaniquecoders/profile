<?php

use CleaniqueCoders\Profile\Models\PhoneType;
use Illuminate\Support\Facades\Schema;
use Workbench\App\Models\User;

beforeEach(function () {
    // Set up any necessary test dependencies (e.g., user model).
    $this->user = User::factory()->create();
});

it('has the phones table', function () {
    expect(Schema::hasTable('phones'))->toBeTrue();
});

it('can create a home phone', function () {
    $phone = $this->user->phones()->create([
        'phone_number' => '+6089259167',
        'is_default' => true,
        'phone_type_id' => PhoneType::HOME,
    ]);

    expect($phone)->not->toBeNull()
        ->and($phone->phone_number)->toBe('+6089259167')
        ->and($phone->is_default)->toBeTrue()
        ->and($phone->phone_type_id)->toBe(PhoneType::HOME);
});

it('can create a mobile phone', function () {
    $phone = $this->user->phones()->create([
        'phone_number' => '+60191234567',
        'is_default' => true,
        'phone_type_id' => PhoneType::MOBILE,
    ]);

    expect($phone)->not->toBeNull()
        ->and($phone->phone_number)->toBe('+60191234567')
        ->and($phone->is_default)->toBeTrue()
        ->and($phone->phone_type_id)->toBe(PhoneType::MOBILE);
});

it('can create an office phone', function () {
    $phone = $this->user->phones()->create([
        'phone_number' => '+60380001000',
        'is_default' => true,
        'phone_type_id' => PhoneType::OFFICE,
    ]);

    expect($phone)->not->toBeNull()
        ->and($phone->phone_number)->toBe('+60380001000')
        ->and($phone->is_default)->toBeTrue()
        ->and($phone->phone_type_id)->toBe(PhoneType::OFFICE);
});

it('can create another phone', function () {
    $phone = $this->user->phones()->create([
        'phone_number' => '+60380001000',
        'is_default' => true,
        'phone_type_id' => PhoneType::OTHER,
    ]);

    expect($phone)->not->toBeNull()
        ->and($phone->phone_number)->toBe('+60380001000')
        ->and($phone->is_default)->toBeTrue()
        ->and($phone->phone_type_id)->toBe(PhoneType::OTHER);
});

it('can create a fax phone', function () {
    $phone = $this->user->phones()->create([
        'phone_number' => '+60380001001',
        'is_default' => true,
        'phone_type_id' => PhoneType::FAX,
    ]);

    expect($phone)->not->toBeNull()
        ->and($phone->phone_number)->toBe('+60380001001')
        ->and($phone->is_default)->toBeTrue()
        ->and($phone->phone_type_id)->toBe(PhoneType::FAX);
});
