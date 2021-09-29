<?php

namespace WeDevBr\Bankly;

use Illuminate\Support\ServiceProvider;

class BanklyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('bankly.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'bankly');

        // Register the main class to use with the facade
        $this->app->singleton('bankly', function () {
            return new Bankly;
        });

        // Register the card management class to use with the facade
        $this->app->singleton('bankly_card', function () {
            return new BanklyCard();
        });

        // Register the pix management class to use with the facade
        $this->app->singleton('bankly_pix', function () {
            return new BanklyPix();
        });
    }
}
