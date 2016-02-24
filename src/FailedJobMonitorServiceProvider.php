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
            __DIR__.'/../config/laravel-failed-jobs-monitor.php' => config_path('laravel-failed-jobs-monitor.php'),
        ], 'config');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-failed-jobs-monitor');

        $this->registerChannels();
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-failed-jobs-monitor.php', 'laravel-failed-jobs-monitor');

        $this->app->singleton(FailedJobNotifier::class);
    }

    public function registerChannels()
    {
        foreach (config('laravel-failed-jobs-monitor.senders') as $sender) {
            $this->app->make(FailedJobNotifier::class)->notifyIfJobFailed($sender);
        }
    }
}
