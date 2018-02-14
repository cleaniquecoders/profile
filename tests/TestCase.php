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

        $this->loadLaravelMigrations(['--database' => 'testbench']);

        $this->artisan('migrate', ['--database' => 'testbench']);
        $this->artisan('profile:seed');
        $this->user           = new User();
        $this->user->name     = 'Cleanique Coders';
        $this->user->email    = 'test@cleaniquecoders.com';
        $this->user->password = bcrypt('password');
        $this->user->save();
    }

    protected function getPackageProviders($app)
    {
        return [
            \CleaniqueCoders\Blueprint\Macro\BlueprintMacroServiceProvider::class,
            \CleaniqueCoders\Profile\ProfileServiceProvider::class,
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
