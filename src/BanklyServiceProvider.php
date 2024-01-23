<?php

namespace WeDevBr\Bankly;

use Illuminate\Support\ServiceProvider;

class BanklyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('bankly.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'bankly');

        // Register the main class to use with the facade
        $this->app->singleton('bankly', fn () => new Bankly());

        // Register the card management class to use with the facade
        $this->app->singleton('bankly_card', fn () => new BanklyCard());

        // Register the TOTP class to use with facade
        $this->app->singleton('bankly_topt', fn () => new BanklyTOTP());

        $this->app->singleton('bankly_pix_claim', fn () => new BanklyPixClaim());

        $this->app->singleton('bankly_billet', fn () => new BanklyBillet());

        $this->app->singleton('bankly_webhook', fn () => new BanklyWebhook());
    }
}
