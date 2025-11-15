<?php

namespace CleaniqueCoders\Profile\Tests;

use CleaniqueCoders\Profile\ProfileServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Attributes\WithMigration;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as Orchestra;

#[WithMigration]
class TestCase extends Orchestra
{
    use RefreshDatabase, WithWorkbench;

    protected function setUp(): void
    {
        parent::setUp();

        // Automatically guess the factory names based on the model name
        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Workbench\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            ProfileServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        // Set up SQLite in-memory for testing
        config()->set('database.default', 'testing');

        config()->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        // Run the migration for the event manager
        $migrations = [
            __DIR__.'/../database/migrations/create_addresses_table.php.stub',
            __DIR__.'/../database/migrations/create_banks_table.php.stub',
            __DIR__.'/../database/migrations/create_countries_table.php.stub',
            __DIR__.'/../database/migrations/create_credentials_table.php.stub',
            __DIR__.'/../database/migrations/create_documents_table.php.stub',
            __DIR__.'/../database/migrations/create_emails_table.php.stub',
            __DIR__.'/../database/migrations/create_emergency_contacts_table.php.stub',
            __DIR__.'/../database/migrations/create_phone_types_table.php.stub',
            __DIR__.'/../database/migrations/create_phones_table.php.stub',
            __DIR__.'/../database/migrations/create_social_media_table.php.stub',
            __DIR__.'/../database/migrations/create_websites_table.php.stub',
        ];

        foreach ($migrations as $key => $value) {
            $migration = include $value;
            $migration->up();
        }
    }
}
