<?php

namespace Signalmetrics\Signal;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Signalmetrics\Signal\Commands\InstallCommand;
use Signalmetrics\Signal\Commands\MigrateSignalCommand;
use Signalmetrics\Signal\Mechanisms\FrontendAssets;
use Signalmetrics\Signal\Middleware\SignalThrottle;

class SignalServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     */
    public function boot(Router $router)
    {
        /**
         * Custom middleware to set max requests sent to Signal.
         */
        RateLimiter::for('signal.throttle', function (Request $request) {
            $limit = config('signal.max_attempts_per_minute');
            return Limit::perMinute($limit)->by(request()->ip());
        });

        /**
         * Load the SQLite Databae
         */
        config(['database.connections.signal' => config('signal.signal_db')]);

        // Publish assets for the Signal package
        $this->publishes(
            [
                __DIR__ . '/../../../dist' => public_path('vendor/signal'),
            ],
            'signal:assets'
        );

        app(FrontendAssets::class)->boot();

        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'signal');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'signal');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/signal.php' => config_path('signal.php'),
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
            $this->commands([
                InstallCommand::class,
                MigrateSignalCommand::class
            ]);

        }

    }

    /**
     * Register the application services.
     */
    public function register()
    {


        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/signal.php', 'signal');

        // Register the main class to use with the facade
        $this->app->singleton('signal', function () {
            return new Signal;
        });

        app(FrontendAssets::class)->register();

        // Register the SignalFrontendAssets class
//        $this->app->singleton(FrontendAssets::class);
    }

}
