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
        $configPath = __DIR__ . '/../config/epochta-sms.php';
        $this->publishes([$configPath => config_path('epochta-sms.php')], 'config');

        if (! class_exists('CreateEpochtaSmsTable')) {
            $timestamp = date('Y_m_d_His', time());

            $migrationPath = __DIR__.'/../database/migrations/create_epochta_sms_table.php.stub';
            $this->publishes([$migrationPath => database_path("/migrations/{$timestamp}_create_epochta_sms_table.php"),
            ], 'migrations');
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/epochta-sms.php','epochta-sms');
        
        $this->app->singleton('epochta-sms', function($app){
            return new Sms();
        });

        $this->app->alias('epochta-sms', EpochtaSms::class);
    }
}
