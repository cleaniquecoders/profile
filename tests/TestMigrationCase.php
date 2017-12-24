<?php

class TestMigrationCase extends Orchestra\Testbench\TestCase
{
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();

        $this->loadLaravelMigrations(['--database' => 'testbench']);

        $this->artisan('migrate', ['--database' => 'testbench']);
        $this->artisan('db:seed', ['--class' => 'CountrySeeder']);
        $this->artisan('db:seed', ['--class' => 'PhoneTypeSeeder']);
    }

    protected function getPackageProviders($app)
    {
        return [
            \CLNQCDRS\Blueprint\Macro\BlueprintMacroServiceProvider::class,
            \CLNQCDRS\Profile\ProfileServiceProvider::class,
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

    /**
     * Test running migration.
     *
     * @test
     */
    public function testRunningMigration()
    {
        $countries = \DB::table('countries')->count();
        $this->assertEquals(241, $countries);
        $phone_types = \DB::table('phone_types')->count();
        $this->assertEquals(4, $phone_types);
    }
}
