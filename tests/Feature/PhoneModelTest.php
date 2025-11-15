<?php

use CleaniqueCoders\Profile\Models\Phone;
use CleaniqueCoders\Profile\Models\PhoneType;
use Illuminate\Support\Facades\Artisan;
use Workbench\App\Models\User;

beforeEach(function () {
    Artisan::call('profile:seed');
    $this->user = User::factory()->create();
    $this->phoneType = PhoneType::where('name', 'Mobile')->first();
});

it('can create a phone with all fields', function () {
    $phone = Phone::create([
        'phoneable_id' => $this->user->id,
        'phoneable_type' => get_class($this->user),
        'phone_type_id' => $this->phoneType->id,
        'phone_number' => '+60123456789',
    ]);

    expect($phone)->not->toBeNull()
        ->and($phone->phone_number)->toBe('+60123456789')
        ->and($phone->phone_type_id)->toBe($this->phoneType->id);
});

it('phone belongs to phoneable model', function () {
    $phone = Phone::create([
        'phoneable_id' => $this->user->id,
        'phoneable_type' => get_class($this->user),
        'phone_type_id' => $this->phoneType->id,
        'phone' => '+60987654321',
    ]);

    expect($phone->phoneable)->toBeInstanceOf(User::class)
        ->and($phone->phoneable->id)->toBe($this->user->id);
});

it('phone belongs to phone type', function () {
    $phone = Phone::create([
        'phoneable_id' => $this->user->id,
        'phoneable_type' => get_class($this->user),
        'phone_type_id' => $this->phoneType->id,
        'phone' => '+60111222333',
    ]);

    expect($phone->type)->toBeInstanceOf(PhoneType::class)
        ->and($phone->type->id)->toBe($this->phoneType->id);
});

it('can filter phones by mobile scope', function () {
    $mobileType = PhoneType::where('name', 'Mobile')->first();
    $homeType = PhoneType::where('name', 'Home')->first();

    Phone::create([
        'phoneable_id' => $this->user->id,
        'phoneable_type' => get_class($this->user),
        'phone_type_id' => $mobileType->id,
        'phone_number' => '+60123000000',
    ]);

    Phone::create([
        'phoneable_id' => $this->user->id,
        'phoneable_type' => get_class($this->user),
        'phone_type_id' => $homeType->id,
        'phone_number' => '+60123111111',
    ]);

    $mobilePhones = Phone::mobile()->get();

    expect($mobilePhones)->toHaveCount(1)
        ->and($mobilePhones->first()->phone_number)->toBe('+60123000000');
});

it('can access phone type name via relationship', function () {
    $phone = Phone::create([
        'phoneable_id' => $this->user->id,
        'phoneable_type' => get_class($this->user),
        'phone_type_id' => $this->phoneType->id,
        'phone_number' => '+60123456789',
    ]);

    expect($phone->type->name)->toBe('Mobile');
});
