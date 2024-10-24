<?php

use CleaniqueCoders\Profile\Models\Country;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    Artisan::call('profile:seed');
});

it('has the config', function () {
    expect(config('profile'))->not->toBeEmpty()
        ->and(config('profile.seeders'))->toContain('CleaniqueCoders\Profile\Database\Seeders\CountrySeeder');
});

it('has a countries table', function () {
    expect(Schema::hasTable('countries'))->toBeTrue();
});

it('has countries data', function () {
    expect(Country::count())->toBe(241);
});
