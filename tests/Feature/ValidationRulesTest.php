<?php

use CleaniqueCoders\Profile\Rules\ValidAddress;
use CleaniqueCoders\Profile\Rules\ValidEmail;
use CleaniqueCoders\Profile\Rules\ValidPhone;
use CleaniqueCoders\Profile\Rules\ValidPostcode;
use Illuminate\Support\Facades\Validator;

// ValidEmail Rule Tests
it('validates correct email format', function () {
    $validator = Validator::make(
        ['email' => 'test@example.com'],
        ['email' => new ValidEmail]
    );

    expect($validator->passes())->toBeTrue();
});

it('rejects invalid email format', function () {
    $validator = Validator::make(
        ['email' => 'invalid-email'],
        ['email' => new ValidEmail]
    );

    expect($validator->fails())->toBeTrue();
});

// ValidPhone Rule Tests
it('validates international phone format', function () {
    $validator = Validator::make(
        ['phone' => '+60123456789'],
        ['phone' => new ValidPhone]
    );

    expect($validator->passes())->toBeTrue();
});

it('validates local phone format', function () {
    $validator = Validator::make(
        ['phone' => '0123456789'],
        ['phone' => new ValidPhone]
    );

    expect($validator->passes())->toBeTrue();
});

it('validates phone with formatting characters', function () {
    $validator = Validator::make(
        ['phone' => '+60 12-345 6789'],
        ['phone' => new ValidPhone]
    );

    expect($validator->passes())->toBeTrue();
});

it('rejects invalid phone format', function () {
    $validator = Validator::make(
        ['phone' => '123'],
        ['phone' => new ValidPhone]
    );

    expect($validator->fails())->toBeTrue();
});

// ValidAddress Rule Tests
it('validates complete address', function () {
    $validator = Validator::make(
        ['address' => [
            'primary' => '123 Main St',
            'city' => 'Kuala Lumpur',
            'state' => 'Federal Territory',
            'postcode' => '50000',
        ]],
        ['address' => ValidAddress::complete()]
    );

    expect($validator->passes())->toBeTrue();
});

it('rejects incomplete address when complete is required', function () {
    $validator = Validator::make(
        ['address' => [
            'primary' => '123 Main St',
            'city' => 'Kuala Lumpur',
        ]],
        ['address' => ValidAddress::complete()]
    );

    expect($validator->fails())->toBeTrue();
});

it('validates address with required fields', function () {
    $validator = Validator::make(
        ['address' => [
            'primary' => '123 Main St',
            'city' => 'Kuala Lumpur',
        ]],
        ['address' => ValidAddress::requiring(['primary', 'city'])]
    );

    expect($validator->passes())->toBeTrue();
});

it('validates address with coordinates', function () {
    $validator = Validator::make(
        ['address' => [
            'primary' => '123 Main St',
            'latitude' => 3.139003,
            'longitude' => 101.686855,
        ]],
        ['address' => ValidAddress::withCoordinates()]
    );

    expect($validator->passes())->toBeTrue();
});

it('rejects address with invalid latitude', function () {
    $validator = Validator::make(
        ['address' => [
            'primary' => '123 Main St',
            'latitude' => 95.0, // Invalid: > 90
            'longitude' => 101.686855,
        ]],
        ['address' => ValidAddress::withCoordinates()]
    );

    expect($validator->fails())->toBeTrue();
});

it('rejects address with invalid longitude', function () {
    $validator = Validator::make(
        ['address' => [
            'primary' => '123 Main St',
            'latitude' => 3.139003,
            'longitude' => 200.0, // Invalid: > 180
        ]],
        ['address' => ValidAddress::withCoordinates()]
    );

    expect($validator->fails())->toBeTrue();
});

// ValidPostcode Rule Tests
it('validates Malaysian postcode', function () {
    $validator = Validator::make(
        ['postcode' => '50000'],
        ['postcode' => ValidPostcode::for('MY')]
    );

    expect($validator->passes())->toBeTrue();
});

it('rejects invalid Malaysian postcode', function () {
    $validator = Validator::make(
        ['postcode' => '123'],
        ['postcode' => ValidPostcode::for('MY')]
    );

    expect($validator->fails())->toBeTrue();
});

it('validates US ZIP code', function () {
    $validator = Validator::make(
        ['postcode' => '90210'],
        ['postcode' => ValidPostcode::for('US')]
    );

    expect($validator->passes())->toBeTrue();
});

it('validates US ZIP+4 code', function () {
    $validator = Validator::make(
        ['postcode' => '90210-1234'],
        ['postcode' => ValidPostcode::for('US')]
    );

    expect($validator->passes())->toBeTrue();
});

it('validates UK postcode', function () {
    $validator = Validator::make(
        ['postcode' => 'SW1A 1AA'],
        ['postcode' => ValidPostcode::for('UK')]
    );

    expect($validator->passes())->toBeTrue();
});

it('validates Singapore postcode', function () {
    $validator = Validator::make(
        ['postcode' => '123456'],
        ['postcode' => ValidPostcode::for('SG')]
    );

    expect($validator->passes())->toBeTrue();
});

it('validates generic postcode format', function () {
    $validator = Validator::make(
        ['postcode' => 'ABC123'],
        ['postcode' => new ValidPostcode]
    );

    expect($validator->passes())->toBeTrue();
});

it('allows empty postcode when not required', function () {
    $validator = Validator::make(
        ['postcode' => ''],
        ['postcode' => new ValidPostcode]
    );

    expect($validator->passes())->toBeTrue();
});
