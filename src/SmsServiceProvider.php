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

        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\SmsSend::class,
                Commands\SmsCheckStatus::class,
                Commands\SmsUpdateStatuses::class,
                Commands\SmsResendUndelivered::class,
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
        $this->mergeConfigFrom(__DIR__.'/../config/epochta-sms.php', 'epochta-sms');
        
        $this->app->singleton('epochta-sms', function () {
            return new Sms();
        });

        $this->app->alias('Sms', EpochtaSms::class);
    }
}
