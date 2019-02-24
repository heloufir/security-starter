<?php

namespace Heloufir\SecurityStarter;

use Heloufir\SecurityStarter\Http\Controllers\ProfileController;
use Heloufir\SecurityStarter\Http\Controllers\RoleController;
use Heloufir\SecurityStarter\Commands\SimplePassportConfiguration;
use Heloufir\SecurityStarter\Http\Middleware\RoleMiddleware;
use Illuminate\Support\ServiceProvider;

class SecurityStarterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Register ProfileController
        $this->app->make(ProfileController::class);

        // Register RoleController
        $this->app->make(RoleController::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Register package commands
        $this->commands([
            SimplePassportConfiguration::class
        ]);

        // Register package routes
        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');

        // Register package migrations
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        // Publish package sources
        $this->publishes([
            __DIR__ . '/config/security-starter.php' => config_path('security-starter.php')
        ]);
    }
}
