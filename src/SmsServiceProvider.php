<?php

namespace Fomvasss\EpochtaService;

use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/epochta-sms.php' => config_path('epochta-sms.php')
        ], 'epochta-sms-config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/epochta-sms.php', 'epochta-sms');
        
        $this->app->singleton('epochta-sms', function () {
            return new Sms();
        });

        //$this->app->alias('epochta-sms', EpochtaSms::class);
    }
}
