<?php

namespace Spatie\FailedJobsMonitor;

use Illuminate\Support\ServiceProvider;

class FailedJobsMonitorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/laravel-failed-jobs-monitor.php' => config_path('laravel-failed-jobs-monitor.php'),
        ], 'config');

        /*
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'skeleton');

        $this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/skeleton'),
        ], 'views');
        */
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-failed-jobs-monitor.php', 'laravel-failed-jobs-monitor');
    }
}
