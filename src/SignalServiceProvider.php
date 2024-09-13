<?php

namespace Signalmetrics\Signal;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class SignalServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {

        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'signal');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'signal');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('signal.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/signal'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/signal'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/signal'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }
        
        Blade::anonymousComponentPath(base_path('vendor/signalmetrics/signal/resources/views/components'));
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'signal');

        // Register the main class to use with the facade
        $this->app->singleton('signal', function () {
            return new Signal;
        });
    }
}
