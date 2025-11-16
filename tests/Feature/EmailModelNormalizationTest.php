<?php

use CleaniqueCoders\Profile\Models\Email;
use Workbench\App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

describe('Email Model Normalization', function () {
    it('normalizes email', function () {
        $email = Email::create([
            'emailable_type' => User::class,
            'emailable_id' => $this->user->id,
            'email' => 'User@Example.COM',
        ]);

        expect($email->normalize())->toBe('user@example.com');
    });

    it('gets canonical form', function () {
        $email = Email::create([
            'emailable_type' => User::class,
            'emailable_id' => $this->user->id,
            'email' => 'John.Doe+test@Gmail.COM',
        ]);

        expect($email->getCanonical())->toBe('johndoe@gmail.com');
    });

    it('gets email domain', function () {
        $email = Email::create([
            'emailable_type' => User::class,
            'emailable_id' => $this->user->id,
            'email' => 'user@example.com',
        ]);

        expect($email->getDomain())->toBe('example.com');
    });

    it('gets email provider', function () {
        $email = Email::create([
            'emailable_type' => User::class,
            'emailable_id' => $this->user->id,
            'email' => 'user@gmail.com',
        ]);

        expect($email->getProvider())->toBe('Gmail');
    });

    it('detects business email', function () {
        $businessEmail = Email::create([
            'emailable_type' => User::class,
            'emailable_id' => $this->user->id,
            'email' => 'user@company.com',
        ]);

        $personalEmail = Email::create([
            'emailable_type' => User::class,
            'emailable_id' => $this->user->id,
            'email' => 'user@gmail.com',
        ]);

        expect($businessEmail->isBusinessEmail())->toBeTrue();
        expect($personalEmail->isBusinessEmail())->toBeFalse();
    });

    it('detects disposable email', function () {
        $disposableEmail = Email::create([
            'emailable_type' => User::class,
            'emailable_id' => $this->user->id,
            'email' => 'user@mailinator.com',
        ]);

        $normalEmail = Email::create([
            'emailable_type' => User::class,
            'emailable_id' => $this->user->id,
            'email' => 'user@gmail.com',
        ]);

        expect($disposableEmail->isDisposable())->toBeTrue();
        expect($normalEmail->isDisposable())->toBeFalse();
    });

    it('standardizes email', function () {
        $email = Email::create([
            'emailable_type' => User::class,
            'emailable_id' => $this->user->id,
            'email' => 'User@Example.COM',
        ]);

        $email->standardize();

        expect($email->email)->toBe('user@example.com');
    });
});
