<?php

namespace Spatie\FailedJobMonitor;

use Illuminate\Support\ServiceProvider;

class FailedJobMonitorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/laravel-failed-job-monitor.php' => config_path('laravel-failed-job-monitor.php'),
        ], 'config');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-failed-job-monitor');

        $this->registerSenders();
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-failed-job-monitor.php', 'laravel-failed-job-monitor');

        $this->app->singleton(FailedJobNotifier::class);
    }

    public function registerSenders()
    {
        foreach (config('laravel-failed-job-monitor.senders') as $sender) {
            $this->app->make(FailedJobNotifier::class)->notifyIfJobFailed($sender);
        }
    }
}
