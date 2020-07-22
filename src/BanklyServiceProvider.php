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
        //
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Register the main class to use with the facade
        $this->app->singleton('bankly', function () {
            return new Bankly;
        });
    }
}
