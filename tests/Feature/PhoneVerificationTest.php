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

it('can generate OTP verification code for phone', function () {
    $phone = Phone::create([
        'phoneable_id' => $this->user->id,
        'phoneable_type' => get_class($this->user),
        'phone_type_id' => $this->phoneType->id,
        'number' => '+60123456789',
    ]);

    $phone->generateVerificationCode();

    expect($phone->verification_code)->not->toBeNull()
        ->and(strlen($phone->verification_code))->toBe(6)
        ->and($phone->verification_code_expires_at)->not->toBeNull()
        ->and($phone->verification_code_expires_at->isFuture())->toBeTrue();
});

it('generates valid 6-digit OTP code', function () {
    $phone = Phone::create([
        'phoneable_id' => $this->user->id,
        'phoneable_type' => get_class($this->user),
        'phone_type_id' => $this->phoneType->id,
        'number' => '+60123456789',
    ]);

    $phone->generateVerificationCode();

    expect($phone->verification_code)->toMatch('/^\d{6}$/');
});

it('can verify phone with correct OTP code', function () {
    $phone = Phone::create([
        'phoneable_id' => $this->user->id,
        'phoneable_type' => get_class($this->user),
        'phone_type_id' => $this->phoneType->id,
        'number' => '+60123456789',
    ]);

    $phone->generateVerificationCode();
    $code = $phone->verification_code;

    $result = $phone->verify($code);

    expect($result)->toBeTrue()
        ->and($phone->verified_at)->not->toBeNull()
        ->and($phone->verification_code)->toBeNull();
});

it('cannot verify phone with incorrect OTP code', function () {
    $phone = Phone::create([
        'phoneable_id' => $this->user->id,
        'phoneable_type' => get_class($this->user),
        'phone_type_id' => $this->phoneType->id,
        'number' => '+60123456789',
    ]);

    $phone->generateVerificationCode();

    $result = $phone->verify('000000');

    expect($result)->toBeFalse()
        ->and($phone->verified_at)->toBeNull();
});

it('cannot verify phone with expired OTP code', function () {
    $phone = Phone::create([
        'phoneable_id' => $this->user->id,
        'phoneable_type' => get_class($this->user),
        'phone_type_id' => $this->phoneType->id,
        'number' => '+60123456789',
    ]);

    $phone->generateVerificationCode(expiresInMinutes: -1); // Expired 1 minute ago

    $code = $phone->verification_code;
    $result = $phone->verify($code);

    expect($result)->toBeFalse()
        ->and($phone->verified_at)->toBeNull();
});

it('can check if phone is verified', function () {
    $phone = Phone::create([
        'phoneable_id' => $this->user->id,
        'phoneable_type' => get_class($this->user),
        'phone_type_id' => $this->phoneType->id,
        'number' => '+60123456789',
    ]);

    expect($phone->isVerified())->toBeFalse()
        ->and($phone->isUnverified())->toBeTrue();

    $phone->markAsVerified();

    expect($phone->isVerified())->toBeTrue()
        ->and($phone->isUnverified())->toBeFalse();
});

it('can mark phone as verified without code', function () {
    $phone = Phone::create([
        'phoneable_id' => $this->user->id,
        'phoneable_type' => get_class($this->user),
        'phone_type_id' => $this->phoneType->id,
        'number' => '+60123456789',
    ]);

    $phone->markAsVerified();

    expect($phone->verified_at)->not->toBeNull()
        ->and($phone->verification_code)->toBeNull();
});

it('can filter verified phones', function () {
    $verifiedPhone = Phone::create([
        'phoneable_id' => $this->user->id,
        'phoneable_type' => get_class($this->user),
        'phone_type_id' => $this->phoneType->id,
        'number' => '+60123456789',
    ]);
    $verifiedPhone->markAsVerified();

    $unverifiedPhone = Phone::create([
        'phoneable_id' => $this->user->id,
        'phoneable_type' => get_class($this->user),
        'phone_type_id' => $this->phoneType->id,
        'number' => '+60987654321',
    ]);

    $verified = Phone::verified()->get();
    $unverified = Phone::unverified()->get();

    expect($verified)->toHaveCount(1)
        ->and($verified->first()->id)->toBe($verifiedPhone->id)
        ->and($unverified)->toHaveCount(1)
        ->and($unverified->first()->id)->toBe($unverifiedPhone->id);
});

it('can customize OTP expiration time', function () {
    $phone = Phone::create([
        'phoneable_id' => $this->user->id,
        'phoneable_type' => get_class($this->user),
        'phone_type_id' => $this->phoneType->id,
        'number' => '+60123456789',
    ]);

    $phone->generateVerificationCode(expiresInMinutes: 30);

    $expiresAt = $phone->verification_code_expires_at;
    $expectedTime = now()->addMinutes(30);

    expect($expiresAt->diffInMinutes($expectedTime, false))->toBeLessThan(1);
});
