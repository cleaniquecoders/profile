<?php

namespace CleaniqueCoders\Profile\Tests;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();

        $this->loadLaravelMigrations(['--database' => 'testbench']);

        $this->artisan('migrate', ['--database' => 'testbench']);
        $this->artisan('profile:seed');
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
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
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
