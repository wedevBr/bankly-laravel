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
        $this->app->singleton('bankly', fn () => new Bankly);
        $this->app->singleton('bankly_card', fn () => new BanklyCard);
        $this->app->singleton('bankly_topt', fn () => new BanklyTOTP);
        $this->app->singleton('bankly_pix_claim', fn () => new BanklyPixClaim);
        $this->app->singleton('bankly_billet', fn () => new BanklyBillet);
        $this->app->singleton('bankly_webhook', fn () => new BanklyWebhook);
        $this->app->singleton('bankly_batch_messages', fn () => new BanklyWebhookBatchMessage);
        $this->app->singleton('bankly_income_report', fn () => new BanklyIncomeReport);
        $this->app->singleton('bankly_legal_agreement', fn () => new BanklyLegalAgreement);
        $this->app->singleton('bankly_open_finance', fn () => new BanklyOpenFinance);
        $this->app->singleton('bankly_automatic_pix', fn () => new BanklyAutomaticPix);
        $this->app->singleton('bankly_scheduled_pix', fn () => new BanklyScheduledPix);
        $this->app->singleton('bankly_customer', fn () => new BanklyCustomer);
        $this->app->singleton('bankly_infraction', fn () => new BanklyInfraction);
    }
}
