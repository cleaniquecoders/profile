<?php

namespace CleaniqueCoders\Profile\Tests;

use CleaniqueCoders\Profile\Tests\Stubs\User;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public $user;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();
        $this->startUp();
    }

    public function tearDown()
    {
        $this->cleanUp();
        parent::tearDown();
    }

    public function startUp()
    {
        $this->publish();
        $this->migrate();
        $this->seedData();
        $this->createAUser();
    }

    public function publish()
    {
        $this->artisan('vendor:publish', ['--tag' => 'profile-factories']);
        $this->artisan('vendor:publish', ['--tag' => 'profile-migrations']);
        $this->artisan('vendor:publish', ['--tag' => 'profile-seeds']);
    }

    public function migrate()
    {
        $this->loadLaravelMigrations(['--database' => 'testbench']);
        $this->artisan('migrate', ['--database' => 'testbench']);
    }

    public function seedData()
    {
        $this->artisan('profile:seed');
    }

    public function createAUser()
    {
        $this->user           = new User();
        $this->user->name     = 'Cleanique Coders';
        $this->user->email    = 'test@cleaniquecoders.com';
        $this->user->password = bcrypt('password');
        $this->user->save();
    }

    public function cleanUp()
    {
        collect()
            ->concat(
                glob(database_path('/factories/*.php'))
            )
            ->concat(
                glob(database_path('/migrations/*.php'))
            )
            ->concat(
                glob(database_path('/seeds/*.php'))
            )->each(function ($path) {
                if (file_exists($path)) {
                    unlink($path);
                }
            });
    }

    protected function getPackageProviders($app)
    {
        return [
            \CleaniqueCoders\Profile\ProfileServiceProvider::class,
            \CleaniqueCoders\Blueprint\Macro\BlueprintMacroServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }
}
