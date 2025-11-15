<?php

use CleaniqueCoders\Profile\Models\Website;
use Workbench\App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('can create a website with all fields', function () {
    $website = Website::create([
        'websiteable_id' => $this->user->id,
        'websiteable_type' => get_class($this->user),
        'url' => 'https://example.com',
        'is_default' => true,
    ]);

    expect($website)->not->toBeNull()
        ->and($website->url)->toBe('https://example.com')
        ->and($website->is_default)->toBeTrue();
});

it('website belongs to websiteable model', function () {
    $website = Website::create([
        'websiteable_id' => $this->user->id,
        'websiteable_type' => get_class($this->user),
        'url' => 'https://johndoe.com',
    ]);

    expect($website->websiteable)->toBeInstanceOf(User::class)
        ->and($website->websiteable->id)->toBe($this->user->id);
});

it('can filter websites by url', function () {
    Website::create([
        'websiteable_id' => $this->user->id,
        'websiteable_type' => get_class($this->user),
        'url' => 'https://default.com',
    ]);

    Website::create([
        'websiteable_id' => $this->user->id,
        'websiteable_type' => get_class($this->user),
        'url' => 'https://other.com',
    ]);

    $specificWebsite = Website::where('url', 'https://default.com')->first();

    expect($specificWebsite)->not->toBeNull()
        ->and($specificWebsite->url)->toBe('https://default.com');
});

it('can create multiple websites for same user', function () {
    Website::create([
        'websiteable_id' => $this->user->id,
        'websiteable_type' => get_class($this->user),
        'url' => 'https://website1.com',
    ]);

    Website::create([
        'websiteable_id' => $this->user->id,
        'websiteable_type' => get_class($this->user),
        'url' => 'https://website2.com',
    ]);

    $websites = Website::where('websiteable_id', $this->user->id)->get();

    expect($websites)->toHaveCount(2);
});
