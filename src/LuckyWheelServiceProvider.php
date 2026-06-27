<?php

namespace Gwebas\LuckyWheel;

use Gwebas\LuckyWheel\Services\LuckyWheelService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class LuckyWheelServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/lucky-wheel.php', 'lucky-wheel');

        $this->app->singleton('lucky-wheel', function ($app) {
            return new LuckyWheelService;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Load routes
        if (file_exists(__DIR__.'/../routes/web.php')) {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        }

        // Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'lucky-wheel');

        // Load translations
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'lucky-wheel');
        $this->loadJsonTranslationsFrom(__DIR__.'/../resources/lang');

        // Apply package locale if configured
        if ($locale = config('lucky-wheel.locale')) {
            app()->setLocale($locale);
        }

        // Register Blade component
        Blade::componentNamespace('Gwebas\\LuckyWheel\\View\\Components', 'lucky-wheel');

        // Publishing config
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/lucky-wheel.php' => config_path('lucky-wheel.php'),
            ], 'lucky-wheel-config');

            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'lucky-wheel-migrations');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/lucky-wheel'),
            ], 'lucky-wheel-views');

            $this->publishes([
                __DIR__.'/../resources/lang' => $this->app->langPath('vendor/lucky-wheel'),
            ], 'lucky-wheel-lang');
        }
    }
}
