<?php

namespace CleaniqueCoders\Profile;

use Illuminate\Support\ServiceProvider;

class ProfileServiceProvider extends ServiceProvider
{
    /**
     * Package Tag Name
     * @var string
     */
    protected $package_tag = 'profile';

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Migrations
         */
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->publishes([
            __DIR__ . '/database/factories' => database_path('factories/'),
        ], $this->package_tag . '-factories');
        $this->publishes([
            __DIR__ . '/database/migrations' => database_path('migrations/'),
        ], $this->package_tag . '-migrations');

        /**
         * Commands
         */
        if ($this->app->runningInConsole()) {
            $this->commands([
                \CleaniqueCoders\Profile\Console\Commands\SeedProfileCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
