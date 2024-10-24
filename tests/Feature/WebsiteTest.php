<?php

use CleaniqueCoders\Profile\Models\Website;
use Illuminate\Support\Facades\Schema;
use Workbench\App\Models\User;

beforeEach(function () {
    // Set up any necessary test dependencies (e.g., user model).
    $this->user = User::factory()->create();
});
it('has the websites table', function () {
    expect(Schema::hasTable('websites'))->toBeTrue();
});

it('has no websites initially', function () {
    expect(Website::count())->toBe(0);
});

it('can create a website', function () {
    $website = $this->user->websites()->create([
        'name' => 'Cleanique Coders',
        'url' => 'https://cleaniquecoders.com',
        'is_default' => true,
    ]);

    expect($website)->not->toBeNull()
        ->and($website->name)->toBe('Cleanique Coders')
        ->and($website->url)->toBe('https://cleaniquecoders.com')
        ->and($website->is_default)->toBeTrue();
});
