<?php

use CleaniqueCoders\Profile\Enums\SocialMediaPlatform;
use CleaniqueCoders\Profile\Models\SocialMedia;
use Illuminate\Support\Facades\Schema;
use Workbench\App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('has a social_media table', function () {
    expect(Schema::hasTable('social_media'))->toBeTrue();
});

it('can create a social media account', function () {
    $socialMedia = SocialMedia::create([
        'socialable_id' => $this->user->id,
        'socialable_type' => get_class($this->user),
        'platform' => SocialMediaPlatform::TWITTER,
        'username' => 'johndoe',
        'url' => 'https://twitter.com/johndoe',
        'is_verified' => true,
        'is_primary' => true,
    ]);

    expect($socialMedia)->not->toBeNull()
        ->and($socialMedia->platform)->toBe(SocialMediaPlatform::TWITTER)
        ->and($socialMedia->username)->toBe('johndoe')
        ->and($socialMedia->is_verified)->toBeTrue()
        ->and($socialMedia->is_primary)->toBeTrue();
});

it('social media belongs to socialable model', function () {
    $socialMedia = SocialMedia::create([
        'socialable_id' => $this->user->id,
        'socialable_type' => get_class($this->user),
        'platform' => SocialMediaPlatform::GITHUB,
        'username' => 'johndoe',
    ]);

    expect($socialMedia->socialable)->toBeInstanceOf(User::class)
        ->and($socialMedia->socialable->id)->toBe($this->user->id);
});

it('can filter social media by primary scope', function () {
    SocialMedia::create([
        'socialable_id' => $this->user->id,
        'socialable_type' => get_class($this->user),
        'platform' => SocialMediaPlatform::TWITTER,
        'username' => 'primary_account',
        'is_primary' => true,
    ]);

    SocialMedia::create([
        'socialable_id' => $this->user->id,
        'socialable_type' => get_class($this->user),
        'platform' => SocialMediaPlatform::GITHUB,
        'username' => 'secondary_account',
        'is_primary' => false,
    ]);

    $primaryAccounts = SocialMedia::primary()->get();

    expect($primaryAccounts)->toHaveCount(1)
        ->and($primaryAccounts->first()->username)->toBe('primary_account');
});

it('can filter social media by verified scope', function () {
    SocialMedia::create([
        'socialable_id' => $this->user->id,
        'socialable_type' => get_class($this->user),
        'platform' => SocialMediaPlatform::TWITTER,
        'username' => 'verified_account',
        'is_verified' => true,
    ]);

    SocialMedia::create([
        'socialable_id' => $this->user->id,
        'socialable_type' => get_class($this->user),
        'platform' => SocialMediaPlatform::GITHUB,
        'username' => 'unverified_account',
        'is_verified' => false,
    ]);

    $verifiedAccounts = SocialMedia::verified()->get();

    expect($verifiedAccounts)->toHaveCount(1)
        ->and($verifiedAccounts->first()->username)->toBe('verified_account');
});

it('can filter social media by platform scope', function () {
    SocialMedia::create([
        'socialable_id' => $this->user->id,
        'socialable_type' => get_class($this->user),
        'platform' => SocialMediaPlatform::TWITTER,
        'username' => 'twitter_account',
    ]);

    SocialMedia::create([
        'socialable_id' => $this->user->id,
        'socialable_type' => get_class($this->user),
        'platform' => SocialMediaPlatform::GITHUB,
        'username' => 'github_account',
    ]);

    $twitterAccounts = SocialMedia::platform(SocialMediaPlatform::TWITTER->value)->get();

    expect($twitterAccounts)->toHaveCount(1)
        ->and($twitterAccounts->first()->platform)->toBe(SocialMediaPlatform::TWITTER);
});

it('user can access social media via HasProfile trait', function () {
    SocialMedia::create([
        'socialable_id' => $this->user->id,
        'socialable_type' => get_class($this->user),
        'platform' => SocialMediaPlatform::TWITTER,
        'username' => 'twitter_account',
    ]);

    SocialMedia::create([
        'socialable_id' => $this->user->id,
        'socialable_type' => get_class($this->user),
        'platform' => SocialMediaPlatform::GITHUB,
        'username' => 'github_account',
    ]);

    $accounts = $this->user->socialMedia;

    expect($accounts)->toHaveCount(2);
});

it('user can get primary social media account', function () {
    SocialMedia::create([
        'socialable_id' => $this->user->id,
        'socialable_type' => get_class($this->user),
        'platform' => SocialMediaPlatform::TWITTER,
        'username' => 'primary_twitter',
        'is_primary' => true,
    ]);

    SocialMedia::create([
        'socialable_id' => $this->user->id,
        'socialable_type' => get_class($this->user),
        'platform' => SocialMediaPlatform::GITHUB,
        'username' => 'secondary_github',
        'is_primary' => false,
    ]);

    $primaryAccount = $this->user->primarySocialMedia();

    expect($primaryAccount)->not->toBeNull()
        ->and($primaryAccount->username)->toBe('primary_twitter')
        ->and($primaryAccount->is_primary)->toBeTrue();
});

it('user can get social media account by platform', function () {
    SocialMedia::create([
        'socialable_id' => $this->user->id,
        'socialable_type' => get_class($this->user),
        'platform' => SocialMediaPlatform::TWITTER,
        'username' => 'twitter_account',
    ]);

    SocialMedia::create([
        'socialable_id' => $this->user->id,
        'socialable_type' => get_class($this->user),
        'platform' => SocialMediaPlatform::GITHUB,
        'username' => 'github_account',
    ]);

    $twitterAccount = $this->user->getSocialMediaByPlatform(SocialMediaPlatform::TWITTER->value);

    expect($twitterAccount)->not->toBeNull()
        ->and($twitterAccount->platform)->toBe(SocialMediaPlatform::TWITTER)
        ->and($twitterAccount->username)->toBe('twitter_account');
});

it('user can check if has social media accounts', function () {
    expect($this->user->hasSocialMedia())->toBeFalse();

    SocialMedia::create([
        'socialable_id' => $this->user->id,
        'socialable_type' => get_class($this->user),
        'platform' => SocialMediaPlatform::TWITTER,
        'username' => 'twitter_account',
    ]);

    expect($this->user->hasSocialMedia())->toBeTrue();
});

it('user can check if has social media account for specific platform', function () {
    expect($this->user->hasSocialMedia(SocialMediaPlatform::TWITTER->value))->toBeFalse();

    SocialMedia::create([
        'socialable_id' => $this->user->id,
        'socialable_type' => get_class($this->user),
        'platform' => SocialMediaPlatform::TWITTER,
        'username' => 'twitter_account',
    ]);

    expect($this->user->hasSocialMedia(SocialMediaPlatform::TWITTER->value))->toBeTrue()
        ->and($this->user->hasSocialMedia(SocialMediaPlatform::GITHUB->value))->toBeFalse();
});
