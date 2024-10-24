<?php

use CleaniqueCoders\Profile\Models\Email;
use Illuminate\Support\Facades\Schema;
use Workbench\App\Models\User;

beforeEach(function () {
    // Set up any necessary test dependencies (e.g., user model).
    $this->user = User::factory()->create();
});

it('has the emails table', function () {
    expect(Schema::hasTable('emails'))->toBeTrue();
});

it('has no emails', function () {
    expect(Email::count())->toBe(0);
});

it('can create an email', function () {
    $email = $this->user->emails()->create([
        'email' => 'info@cleaniquecoders.com',
        'is_default' => true,
    ]);

    expect($email)->not->toBeNull()
        ->and($email->email)->toBe('info@cleaniquecoders.com')
        ->and($email->is_default)->toBeTrue();
});
