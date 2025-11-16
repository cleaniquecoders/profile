<?php

use CleaniqueCoders\Profile\Models\Address;
use CleaniqueCoders\Profile\Models\Country;
use CleaniqueCoders\Profile\Models\Email;
use CleaniqueCoders\Profile\Models\Phone;
use CleaniqueCoders\Profile\Services\EncryptionManager;
use Illuminate\Support\Facades\Artisan;
use Workbench\App\Models\User;

beforeEach(function () {
    Artisan::call('profile:seed');
    $this->user = User::factory()->create();
});

describe('EncryptionManager', function () {
    it('encrypts a value', function () {
        $value = 'secret';
        $encrypted = EncryptionManager::encrypt($value);

        expect($encrypted)->not->toBe($value);
        expect(strlen($encrypted))->toBeGreaterThan(strlen($value));
    });

    it('decrypts a value', function () {
        $value = 'secret';
        $encrypted = EncryptionManager::encrypt($value);
        $decrypted = EncryptionManager::decrypt($encrypted);

        expect($decrypted)->toBe($value);
    });

    it('detects if a value is encrypted', function () {
        $value = 'secret';
        $encrypted = EncryptionManager::encrypt($value);

        expect(EncryptionManager::isEncrypted($encrypted))->toBeTrue();
        expect(EncryptionManager::isEncrypted($value))->toBeFalse();
    });

    it('encrypts emails for a model', function () {
        Email::create([
            'emailable_type' => User::class,
            'emailable_id' => $this->user->id,
            'email' => 'user1@example.com',
        ]);

        Email::create([
            'emailable_type' => User::class,
            'emailable_id' => $this->user->id,
            'email' => 'user2@example.com',
        ]);

        $count = EncryptionManager::encryptEmails($this->user->fresh());

        expect($count)->toBe(2);

        $emails = Email::where('emailable_id', $this->user->id)->get();
        foreach ($emails as $email) {
            expect(EncryptionManager::isEncrypted($email->getAttributes()['email']))->toBeTrue();
        }
    });

    it('decrypts emails for a model', function () {
        Email::create([
            'emailable_type' => User::class,
            'emailable_id' => $this->user->id,
            'email' => 'user1@example.com',
        ]);

        EncryptionManager::encryptEmails($this->user->fresh());
        $count = EncryptionManager::decryptEmails($this->user->fresh());

        expect($count)->toBe(1);

        $email = Email::where('emailable_id', $this->user->id)->first();
        expect(EncryptionManager::isEncrypted($email->getAttributes()['email']))->toBeFalse();
    });

    it('encrypts phones for a model', function () {
        Phone::create([
            'phoneable_type' => User::class,
            'phoneable_id' => $this->user->id,
            'number' => '0123456789',
            'phone_type_id' => 1,
        ]);

        Phone::create([
            'phoneable_type' => User::class,
            'phoneable_id' => $this->user->id,
            'number' => '0198765432',
            'phone_type_id' => 1,
        ]);

        $count = EncryptionManager::encryptPhones($this->user->fresh());

        expect($count)->toBe(2);

        $phones = Phone::where('phoneable_id', $this->user->id)->get();
        foreach ($phones as $phone) {
            expect(EncryptionManager::isEncrypted($phone->getAttributes()['number']))->toBeTrue();
        }
    });

    it('decrypts phones for a model', function () {
        Phone::create([
            'phoneable_type' => User::class,
            'phoneable_id' => $this->user->id,
            'number' => '0123456789',
            'phone_type_id' => 1,
        ]);

        EncryptionManager::encryptPhones($this->user->fresh());
        $count = EncryptionManager::decryptPhones($this->user->fresh());

        expect($count)->toBe(1);

        $phone = Phone::where('phoneable_id', $this->user->id)->first();
        expect(EncryptionManager::isEncrypted($phone->getAttributes()['number']))->toBeFalse();
    });

    it('encrypts addresses for a model', function () {
        $country = Country::first();

        Address::create([
            'addressable_type' => User::class,
            'addressable_id' => $this->user->id,
            'primary' => '123 Main Street',
            'city' => 'Kuala Lumpur',
            'state' => 'Selangor',
            'postcode' => '12345',
            'country_id' => $country->id,
        ]);

        $count = EncryptionManager::encryptAddresses($this->user->fresh());

        expect($count)->toBe(1);

        $address = Address::where('addressable_id', $this->user->id)->first();
        expect(EncryptionManager::isEncrypted($address->getAttributes()['primary']))->toBeTrue();
        expect(EncryptionManager::isEncrypted($address->getAttributes()['city']))->toBeTrue();
        expect(EncryptionManager::isEncrypted($address->getAttributes()['state']))->toBeTrue();
        expect(EncryptionManager::isEncrypted($address->getAttributes()['postcode']))->toBeTrue();
    });

    it('decrypts addresses for a model', function () {
        $country = Country::first();

        Address::create([
            'addressable_type' => User::class,
            'addressable_id' => $this->user->id,
            'primary' => '123 Main Street',
            'city' => 'Kuala Lumpur',
            'postcode' => '12345',
            'country_id' => $country->id,
        ]);

        EncryptionManager::encryptAddresses($this->user->fresh());
        $count = EncryptionManager::decryptAddresses($this->user->fresh());

        expect($count)->toBe(1);

        $address = Address::where('addressable_id', $this->user->id)->first();
        expect(EncryptionManager::isEncrypted($address->getAttributes()['primary']))->toBeFalse();
        expect(EncryptionManager::isEncrypted($address->getAttributes()['city']))->toBeFalse();
        expect(EncryptionManager::isEncrypted($address->getAttributes()['postcode']))->toBeFalse();
    });

    it('encrypts all profile data', function () {
        $country = Country::first();

        Email::create([
            'emailable_type' => User::class,
            'emailable_id' => $this->user->id,
            'email' => 'user@example.com',
        ]);

        Phone::create([
            'phoneable_type' => User::class,
            'phoneable_id' => $this->user->id,
            'number' => '0123456789',
            'phone_type_id' => 1,
        ]);

        Address::create([
            'addressable_type' => User::class,
            'addressable_id' => $this->user->id,
            'primary' => '123 Main Street',
            'city' => 'Kuala Lumpur',
            'postcode' => '12345',
            'country_id' => $country->id,
        ]);

        $counts = EncryptionManager::encryptAll($this->user->fresh());

        expect($counts['emails'])->toBe(1);
        expect($counts['phones'])->toBe(1);
        expect($counts['addresses'])->toBe(1);
    });

    it('hashes a value', function () {
        $value = 'secret';
        $hash = EncryptionManager::hash($value);

        expect($hash)->not->toBe($value);
        expect(strlen($hash))->toBe(64); // SHA-256 produces 64-character hex string
    });

    it('verifies a hash', function () {
        $value = 'secret';
        $hash = EncryptionManager::hash($value);

        expect(EncryptionManager::verifyHash($value, $hash))->toBeTrue();
        expect(EncryptionManager::verifyHash('wrong', $hash))->toBeFalse();
    });
});
