<?php

use CleaniqueCoders\Profile\Models\Email;
use Workbench\App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('can generate verification token for email', function () {
    $email = Email::create([
        'emailable_id' => $this->user->id,
        'emailable_type' => get_class($this->user),
        'email' => 'test@example.com',
    ]);

    $email->generateVerificationToken();

    expect($email->verification_token)->not->toBeNull()
        ->and($email->verification_token_expires_at)->not->toBeNull()
        ->and($email->verification_token_expires_at->isFuture())->toBeTrue();
});

it('can verify email with correct token', function () {
    $email = Email::create([
        'emailable_id' => $this->user->id,
        'emailable_type' => get_class($this->user),
        'email' => 'test@example.com',
    ]);

    $email->generateVerificationToken();
    $token = $email->verification_token;

    $result = $email->verify($token);

    expect($result)->toBeTrue()
        ->and($email->verified_at)->not->toBeNull()
        ->and($email->verification_token)->toBeNull();
});

it('cannot verify email with incorrect token', function () {
    $email = Email::create([
        'emailable_id' => $this->user->id,
        'emailable_type' => get_class($this->user),
        'email' => 'test@example.com',
    ]);

    $email->generateVerificationToken();

    $result = $email->verify('wrong-token');

    expect($result)->toBeFalse()
        ->and($email->verified_at)->toBeNull();
});

it('cannot verify email with expired token', function () {
    $email = Email::create([
        'emailable_id' => $this->user->id,
        'emailable_type' => get_class($this->user),
        'email' => 'test@example.com',
    ]);

    $email->generateVerificationToken(expiresInMinutes: -1); // Expired 1 minute ago

    $token = $email->verification_token;
    $result = $email->verify($token);

    expect($result)->toBeFalse()
        ->and($email->verified_at)->toBeNull();
});

it('can check if email is verified', function () {
    $email = Email::create([
        'emailable_id' => $this->user->id,
        'emailable_type' => get_class($this->user),
        'email' => 'test@example.com',
    ]);

    expect($email->isVerified())->toBeFalse()
        ->and($email->isUnverified())->toBeTrue();

    $email->markAsVerified();

    expect($email->isVerified())->toBeTrue()
        ->and($email->isUnverified())->toBeFalse();
});

it('can mark email as verified without token', function () {
    $email = Email::create([
        'emailable_id' => $this->user->id,
        'emailable_type' => get_class($this->user),
        'email' => 'test@example.com',
    ]);

    $email->markAsVerified();

    expect($email->verified_at)->not->toBeNull()
        ->and($email->verification_token)->toBeNull();
});

it('can filter verified emails', function () {
    $verifiedEmail = Email::create([
        'emailable_id' => $this->user->id,
        'emailable_type' => get_class($this->user),
        'email' => 'verified@example.com',
    ]);
    $verifiedEmail->markAsVerified();

    $unverifiedEmail = Email::create([
        'emailable_id' => $this->user->id,
        'emailable_type' => get_class($this->user),
        'email' => 'unverified@example.com',
    ]);

    $verified = Email::verified()->get();
    $unverified = Email::unverified()->get();

    expect($verified)->toHaveCount(1)
        ->and($verified->first()->id)->toBe($verifiedEmail->id)
        ->and($unverified)->toHaveCount(1)
        ->and($unverified->first()->id)->toBe($unverifiedEmail->id);
});

it('can customize token expiration time', function () {
    $email = Email::create([
        'emailable_id' => $this->user->id,
        'emailable_type' => get_class($this->user),
        'email' => 'test@example.com',
    ]);

    $email->generateVerificationToken(expiresInMinutes: 120);

    $expiresAt = $email->verification_token_expires_at;
    $expectedTime = now()->addMinutes(120);

    expect($expiresAt->diffInMinutes($expectedTime, false))->toBeLessThan(1);
});
