<?php

use CleaniqueCoders\Profile\Models\Address;
use CleaniqueCoders\Profile\Models\Country;
use CleaniqueCoders\Profile\Models\Email;
use CleaniqueCoders\Profile\Models\Phone;
use CleaniqueCoders\Profile\Services\DuplicateDetector;
use Illuminate\Support\Facades\Artisan;
use Workbench\App\Models\User;

beforeEach(function () {
    Artisan::call('profile:seed');
    $this->user = User::factory()->create();
});

describe('DuplicateDetector - Emails', function () {
    it('finds duplicate emails based on canonical form', function () {
        $email1 = Email::create([
            'emailable_type' => User::class,
            'emailable_id' => $this->user->id,
            'email' => 'john.doe@gmail.com',
        ]);

        $email2 = Email::create([
            'emailable_type' => User::class,
            'emailable_id' => $this->user->id,
            'email' => 'johndoe@gmail.com',
        ]);

        $duplicates = DuplicateDetector::findDuplicateEmails($email1);

        expect($duplicates)->toHaveCount(1);
        expect($duplicates->first()->id)->toBe($email2->id);
    });

    it('finds duplicate emails with plus addressing', function () {
        $email1 = Email::create([
            'emailable_type' => User::class,
            'emailable_id' => $this->user->id,
            'email' => 'user@example.com',
        ]);

        $email2 = Email::create([
            'emailable_type' => User::class,
            'emailable_id' => $this->user->id,
            'email' => 'user+tag@example.com',
        ]);

        $duplicates = DuplicateDetector::findDuplicateEmails($email1);

        expect($duplicates)->toHaveCount(1);
        expect($duplicates->first()->id)->toBe($email2->id);
    });

    it('merges duplicate emails', function () {
        $email1 = Email::create([
            'emailable_type' => User::class,
            'emailable_id' => $this->user->id,
            'email' => 'user@example.com',
        ]);

        $email2 = Email::create([
            'emailable_type' => User::class,
            'emailable_id' => $this->user->id,
            'email' => 'user+tag@example.com',
            'verified_at' => now(),
        ]);

        $merged = DuplicateDetector::mergeEmails($email1, $email2);

        expect($merged->id)->toBe($email1->id);
        expect($merged->isVerified())->toBeTrue();
        expect(Email::find($email2->id))->toBeNull();
    });
});

describe('DuplicateDetector - Phones', function () {
    it('finds duplicate phones based on E.164 format', function () {
        $phone1 = Phone::create([
            'phoneable_type' => User::class,
            'phoneable_id' => $this->user->id,
            'number' => '0123456789',
            'phone_type_id' => 1,
        ]);

        $phone2 = Phone::create([
            'phoneable_type' => User::class,
            'phoneable_id' => $this->user->id,
            'number' => '+60123456789',
            'phone_type_id' => 1,
        ]);

        $duplicates = DuplicateDetector::findDuplicatePhones($phone1, '60');

        expect($duplicates)->toHaveCount(1);
        expect($duplicates->first()->id)->toBe($phone2->id);
    });

    it('merges duplicate phones', function () {
        $phone1 = Phone::create([
            'phoneable_type' => User::class,
            'phoneable_id' => $this->user->id,
            'number' => '0123456789',
            'phone_type_id' => 1,
        ]);

        $phone2 = Phone::create([
            'phoneable_type' => User::class,
            'phoneable_id' => $this->user->id,
            'number' => '+60123456789',
            'phone_type_id' => 1,
            'verified_at' => now(),
        ]);

        $merged = DuplicateDetector::mergePhones($phone1, $phone2);

        expect($merged->id)->toBe($phone1->id);
        expect($merged->isVerified())->toBeTrue();
        expect(Phone::find($phone2->id))->toBeNull();
    });
});

describe('DuplicateDetector - Addresses', function () {
    it('calculates address similarity', function () {
        $country = Country::first();

        $address1 = Address::create([
            'addressable_type' => User::class,
            'addressable_id' => $this->user->id,
            'primary' => '123 Main Street',
            'city' => 'Kuala Lumpur',
            'state' => 'Selangor',
            'postcode' => '12345',
            'country_id' => $country->id,
        ]);

        $address2 = Address::create([
            'addressable_type' => User::class,
            'addressable_id' => $this->user->id,
            'primary' => '123 Main Street',
            'city' => 'Kuala Lumpur',
            'state' => 'Selangor',
            'postcode' => '12345',
            'country_id' => $country->id,
        ]);

        $similarity = DuplicateDetector::calculateAddressSimilarity($address1, $address2);

        expect($similarity)->toBe(1.0);
    });

    it('finds duplicate addresses', function () {
        $country = Country::first();

        $address1 = Address::create([
            'addressable_type' => User::class,
            'addressable_id' => $this->user->id,
            'primary' => '123 Main Street',
            'city' => 'Kuala Lumpur',
            'state' => 'Selangor',
            'postcode' => '12345',
            'country_id' => $country->id,
        ]);

        $address2 = Address::create([
            'addressable_type' => User::class,
            'addressable_id' => $this->user->id,
            'primary' => '123 Main Street',
            'city' => 'Kuala Lumpur',
            'state' => 'Selangor',
            'postcode' => '12345',
            'country_id' => $country->id,
        ]);

        $duplicates = DuplicateDetector::findDuplicateAddresses($address1);

        expect($duplicates)->toHaveCount(1);
        expect($duplicates->first()->id)->toBe($address2->id);
    });

    it('merges duplicate addresses', function () {
        $country = Country::first();

        $address1 = Address::create([
            'addressable_type' => User::class,
            'addressable_id' => $this->user->id,
            'primary' => '123 Main Street',
            'city' => 'Kuala Lumpur',
            'postcode' => '12345',
            'country_id' => $country->id,
        ]);

        $address2 = Address::create([
            'addressable_type' => User::class,
            'addressable_id' => $this->user->id,
            'primary' => '123 Main Street',
            'city' => 'Kuala Lumpur',
            'postcode' => '12345',
            'country_id' => $country->id,
            'latitude' => 3.1390,
            'longitude' => 101.6869,
        ]);

        $merged = DuplicateDetector::mergeAddresses($address1, $address2);

        expect($merged->id)->toBe($address1->id);
        expect($merged->hasCoordinates())->toBeTrue();
        expect(Address::find($address2->id))->toBeNull();
    });
});

describe('DuplicateDetector - Batch Operations', function () {
    it('finds all duplicates for a model', function () {
        Email::create([
            'emailable_type' => User::class,
            'emailable_id' => $this->user->id,
            'email' => 'user@example.com',
        ]);

        Email::create([
            'emailable_type' => User::class,
            'emailable_id' => $this->user->id,
            'email' => 'user+tag@example.com',
        ]);

        $duplicates = DuplicateDetector::findAllDuplicates($this->user->fresh());

        expect($duplicates['emails'])->not->toBeEmpty();
    });

    it('auto-merges all duplicates', function () {
        Email::create([
            'emailable_type' => User::class,
            'emailable_id' => $this->user->id,
            'email' => 'user@example.com',
        ]);

        Email::create([
            'emailable_type' => User::class,
            'emailable_id' => $this->user->id,
            'email' => 'user+tag@example.com',
        ]);

        $merged = DuplicateDetector::autoMergeDuplicates($this->user->fresh());

        expect($merged['emails'])->toBeGreaterThan(0);
        expect($this->user->fresh()->emails)->toHaveCount(1);
    });
});
