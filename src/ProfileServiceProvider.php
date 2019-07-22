<?php

namespace CleaniqueCoders\Profile;

use Illuminate\Support\ServiceProvider;

class ProfileServiceProvider extends ServiceProvider
{
    /**
     * Package Tag Name.
     *
     * @var string
     */
    protected $package_tag = 'profile';

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Configuration
         */
        $this->publishes([
            __DIR__ . '/../config/profile.php' => config_path('profile.php'),
        ], 'profile');
        $this->mergeConfigFrom(
            __DIR__ . '/../config/profile.php', 'profile'
        );

        /*
         * Migrations
         */
        $this->publishes([
            __DIR__ . '/../stubs/database/factories' => database_path('factories/'),
        ], $this->package_tag . '-factories');
        $this->publishes([
            __DIR__ . '/../stubs/database/migrations' => database_path('migrations/'),
        ], $this->package_tag . '-migrations');
        $this->publishes([
            __DIR__ . '/../database/seeds' => database_path('seeds/'),
        ], $this->package_tag . '-seeds');

        /*
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
     */
    public function register()
    {
    }
}
