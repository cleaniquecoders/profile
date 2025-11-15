<?php

use CleaniqueCoders\Profile\Enums\PhoneType;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Workbench\App\Models\User;

use function Pest\Laravel\assertDatabaseHas;

beforeEach(function () {
    // Set up any necessary test dependencies (e.g., user model).
    $this->user = User::factory()->create();

    Artisan::call('profile:seed');
});
it('has the correct config', function () {
    expect(config('profile'))->not->toBeEmpty()
        ->and(config('profile.seeders'))->toContain('CleaniqueCoders\Profile\Database\Seeders\PhoneTypeSeeder');
});

it('has the phone types table', function () {
    expect(Schema::hasTable('phone_types'))->toBeTrue();
});

it('has five phone types', function () {
    $count = DB::table('phone_types')->count();
    expect($count)->toBe(5);
});

it('has common phone types in config', function () {
    expect(config('profile.enums.phone_type'))->toBe(PhoneType::class);

    $phoneTypes = PhoneType::cases();
    expect($phoneTypes)->toHaveCount(5);

    $expectedTypes = ['Home', 'Mobile', 'Office', 'Other', 'Fax'];
    $labels = array_map(fn ($case) => $case->label(), $phoneTypes);

    foreach ($expectedTypes as $type) {
        expect($labels)->toContain($type);
    }
});

it('has common phone types in the database', function () {
    Artisan::call('db:seed', [
        '--class' => \CleaniqueCoders\Profile\Database\Seeders\PhoneTypeSeeder::class,
    ]);

    assertDatabaseHas('phone_types', ['name' => 'Home']);
    assertDatabaseHas('phone_types', ['name' => 'Mobile']);
    assertDatabaseHas('phone_types', ['name' => 'Office']);
    assertDatabaseHas('phone_types', ['name' => 'Other']);
    assertDatabaseHas('phone_types', ['name' => 'Fax']);
});
