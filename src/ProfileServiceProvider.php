<?php

namespace CLNQCDRS\Profile;

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
            __DIR__ . '/database/migrations' => database_path('migrations/'),
        ], $this->package_tag . '-migrations');
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
