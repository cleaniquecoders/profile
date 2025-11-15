<?php

use CleaniqueCoders\Profile\Enums\CredentialType;
use CleaniqueCoders\Profile\Models\Credential;
use Illuminate\Support\Facades\Schema;
use Workbench\App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('has a credentials table', function () {
    expect(Schema::hasTable('credentials'))->toBeTrue();
});

it('can create a credential', function () {
    $credential = Credential::create([
        'credentialable_id' => $this->user->id,
        'credentialable_type' => get_class($this->user),
        'type' => CredentialType::LICENSE,
        'title' => 'Professional License',
        'issuer' => 'Professional Board',
        'number' => 'PL-12345',
        'issued_at' => now()->subYear(),
        'expires_at' => now()->addYear(),
        'is_verified' => true,
    ]);

    expect($credential)->not->toBeNull()
        ->and($credential->title)->toBe('Professional License')
        ->and($credential->type)->toBe(CredentialType::LICENSE)
        ->and($credential->is_verified)->toBeTrue();
});

it('credential belongs to credentialable model', function () {
    $credential = Credential::create([
        'credentialable_id' => $this->user->id,
        'credentialable_type' => get_class($this->user),
        'type' => CredentialType::CERTIFICATION,
        'title' => 'Certification',
    ]);

    expect($credential->credentialable)->toBeInstanceOf(User::class)
        ->and($credential->credentialable->id)->toBe($this->user->id);
});

it('can filter credentials by verified scope', function () {
    Credential::create([
        'credentialable_id' => $this->user->id,
        'credentialable_type' => get_class($this->user),
        'type' => CredentialType::LICENSE,
        'title' => 'Verified License',
        'is_verified' => true,
    ]);

    Credential::create([
        'credentialable_id' => $this->user->id,
        'credentialable_type' => get_class($this->user),
        'type' => CredentialType::CERTIFICATION,
        'title' => 'Unverified Certification',
        'is_verified' => false,
    ]);

    $verifiedCredentials = Credential::verified()->get();

    expect($verifiedCredentials)->toHaveCount(1)
        ->and($verifiedCredentials->first()->title)->toBe('Verified License');
});

it('can filter credentials by type scope', function () {
    Credential::create([
        'credentialable_id' => $this->user->id,
        'credentialable_type' => get_class($this->user),
        'type' => CredentialType::LICENSE,
        'title' => 'License',
    ]);

    Credential::create([
        'credentialable_id' => $this->user->id,
        'credentialable_type' => get_class($this->user),
        'type' => CredentialType::CERTIFICATION,
        'title' => 'Certification',
    ]);

    $licenses = Credential::type(CredentialType::LICENSE->value)->get();

    expect($licenses)->toHaveCount(1)
        ->and($licenses->first()->type)->toBe(CredentialType::LICENSE);
});

it('can filter active credentials using scope', function () {
    Credential::create([
        'credentialable_id' => $this->user->id,
        'credentialable_type' => get_class($this->user),
        'type' => CredentialType::LICENSE,
        'title' => 'Active License',
        'expires_at' => now()->addYear(),
    ]);

    Credential::create([
        'credentialable_id' => $this->user->id,
        'credentialable_type' => get_class($this->user),
        'type' => CredentialType::LICENSE,
        'title' => 'No Expiry License',
        'expires_at' => null,
    ]);

    Credential::create([
        'credentialable_id' => $this->user->id,
        'credentialable_type' => get_class($this->user),
        'type' => CredentialType::CERTIFICATION,
        'title' => 'Expired Certification',
        'expires_at' => now()->subDay(),
    ]);

    $activeCredentials = Credential::active()->get();

    expect($activeCredentials)->toHaveCount(2);
});

it('can filter expired credentials using scope', function () {
    Credential::create([
        'credentialable_id' => $this->user->id,
        'credentialable_type' => get_class($this->user),
        'type' => CredentialType::LICENSE,
        'title' => 'Expired License',
        'expires_at' => now()->subYear(),
    ]);

    Credential::create([
        'credentialable_id' => $this->user->id,
        'credentialable_type' => get_class($this->user),
        'type' => CredentialType::CERTIFICATION,
        'title' => 'Active Certification',
        'expires_at' => now()->addYear(),
    ]);

    $expiredCredentials = Credential::expired()->get();

    expect($expiredCredentials)->toHaveCount(1)
        ->and($expiredCredentials->first()->title)->toBe('Expired License');
});

it('user can access credentials via HasProfile trait', function () {
    Credential::create([
        'credentialable_id' => $this->user->id,
        'credentialable_type' => get_class($this->user),
        'type' => CredentialType::LICENSE,
        'title' => 'User License',
    ]);

    Credential::create([
        'credentialable_id' => $this->user->id,
        'credentialable_type' => get_class($this->user),
        'type' => CredentialType::DEGREE,
        'title' => 'User Degree',
    ]);

    $credentials = $this->user->credentials;

    expect($credentials)->toHaveCount(2);
});

it('user can get active credentials', function () {
    Credential::create([
        'credentialable_id' => $this->user->id,
        'credentialable_type' => get_class($this->user),
        'type' => CredentialType::LICENSE,
        'title' => 'Active License',
        'expires_at' => now()->addYear(),
    ]);

    Credential::create([
        'credentialable_id' => $this->user->id,
        'credentialable_type' => get_class($this->user),
        'type' => CredentialType::LICENSE,
        'title' => 'Expired License',
        'expires_at' => now()->subDay(),
    ]);

    $activeCredentials = $this->user->activeCredentials();

    expect($activeCredentials)->toHaveCount(1)
        ->and($activeCredentials->first()->title)->toBe('Active License');
});

it('user can get expired credentials', function () {
    Credential::create([
        'credentialable_id' => $this->user->id,
        'credentialable_type' => get_class($this->user),
        'type' => CredentialType::LICENSE,
        'title' => 'Expired License',
        'expires_at' => now()->subYear(),
    ]);

    Credential::create([
        'credentialable_id' => $this->user->id,
        'credentialable_type' => get_class($this->user),
        'type' => CredentialType::CERTIFICATION,
        'title' => 'Active Certification',
        'expires_at' => now()->addYear(),
    ]);

    $expiredCredentials = $this->user->expiredCredentials();

    expect($expiredCredentials)->toHaveCount(1)
        ->and($expiredCredentials->first()->title)->toBe('Expired License');
});

it('user can get credentials by type', function () {
    Credential::create([
        'credentialable_id' => $this->user->id,
        'credentialable_type' => get_class($this->user),
        'type' => CredentialType::LICENSE,
        'title' => 'License 1',
    ]);

    Credential::create([
        'credentialable_id' => $this->user->id,
        'credentialable_type' => get_class($this->user),
        'type' => CredentialType::LICENSE,
        'title' => 'License 2',
    ]);

    Credential::create([
        'credentialable_id' => $this->user->id,
        'credentialable_type' => get_class($this->user),
        'type' => CredentialType::DEGREE,
        'title' => 'Degree',
    ]);

    $licenses = $this->user->getCredentialsByType(CredentialType::LICENSE->value);

    expect($licenses)->toHaveCount(2);
});

it('user can check if has active credentials', function () {
    expect($this->user->hasActiveCredentials())->toBeFalse();

    Credential::create([
        'credentialable_id' => $this->user->id,
        'credentialable_type' => get_class($this->user),
        'type' => CredentialType::LICENSE,
        'title' => 'Active License',
        'expires_at' => now()->addYear(),
    ]);

    expect($this->user->hasActiveCredentials())->toBeTrue();
});

it('user can check if has credential of specific type', function () {
    expect($this->user->hasCredential(CredentialType::LICENSE->value))->toBeFalse();

    Credential::create([
        'credentialable_id' => $this->user->id,
        'credentialable_type' => get_class($this->user),
        'type' => CredentialType::LICENSE,
        'title' => 'License',
    ]);

    expect($this->user->hasCredential(CredentialType::LICENSE->value))->toBeTrue()
        ->and($this->user->hasCredential(CredentialType::DEGREE->value))->toBeFalse();
});
