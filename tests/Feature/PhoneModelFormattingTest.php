<?php

use CleaniqueCoders\Profile\Models\Phone;
use Workbench\App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

describe('Phone Model Formatting', function () {
    it('formats phone to E.164', function () {
        $phone = Phone::create([
            'phoneable_type' => User::class,
            'phoneable_id' => $this->user->id,
            'number' => '0123456789',
            'phone_type_id' => 1,
        ]);

        expect($phone->toE164('60'))->toBe('+60123456789');
    });

    it('formats phone to national', function () {
        $phone = Phone::create([
            'phoneable_type' => User::class,
            'phoneable_id' => $this->user->id,
            'number' => '+60123456789',
            'phone_type_id' => 1,
        ]);

        expect($phone->toNational('60'))->toBe('0123456789');
    });

    it('formats phone to readable', function () {
        $phone = Phone::create([
            'phoneable_type' => User::class,
            'phoneable_id' => $this->user->id,
            'number' => '0123456789',
            'phone_type_id' => 1,
        ]);

        expect($phone->toReadable('60'))->toBe('+60 12 345 6789');
    });

    it('standardizes phone number', function () {
        $phone = Phone::create([
            'phoneable_type' => User::class,
            'phoneable_id' => $this->user->id,
            'number' => '0123456789',
            'phone_type_id' => 1,
        ]);

        $phone->standardize('60');

        expect($phone->number)->toBe('+60123456789');
    });
});
