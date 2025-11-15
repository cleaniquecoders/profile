<?php

use CleaniqueCoders\Profile\Models\Email;
use Workbench\App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('can create an email with all fields', function () {
    $email = Email::create([
        'emailable_id' => $this->user->id,
        'emailable_type' => get_class($this->user),
        'email' => 'test@example.com',
        'is_default' => true,
    ]);

    expect($email)->not->toBeNull()
        ->and($email->email)->toBe('test@example.com')
        ->and($email->is_default)->toBeTrue();
});

it('email belongs to emailable model', function () {
    $email = Email::create([
        'emailable_id' => $this->user->id,
        'emailable_type' => get_class($this->user),
        'email' => 'user@example.com',
    ]);

    expect($email->emailable)->toBeInstanceOf(User::class)
        ->and($email->emailable->id)->toBe($this->user->id);
});

it('can filter emails by is_default flag', function () {
    Email::create([
        'emailable_id' => $this->user->id,
        'emailable_type' => get_class($this->user),
        'email' => 'default@example.com',
        'is_default' => true,
    ]);

    Email::create([
        'emailable_id' => $this->user->id,
        'emailable_type' => get_class($this->user),
        'email' => 'other@example.com',
        'is_default' => false,
    ]);

    $defaultEmails = Email::where('is_default', true)->get();

    expect($defaultEmails)->toHaveCount(1)
        ->and($defaultEmails->first()->email)->toBe('default@example.com');
});

it('can create multiple emails for same user', function () {
    Email::create([
        'emailable_id' => $this->user->id,
        'emailable_type' => get_class($this->user),
        'email' => 'email1@example.com',
    ]);

    Email::create([
        'emailable_id' => $this->user->id,
        'emailable_type' => get_class($this->user),
        'email' => 'email2@example.com',
    ]);

    $emails = Email::where('emailable_id', $this->user->id)->get();

    expect($emails)->toHaveCount(2);
});
