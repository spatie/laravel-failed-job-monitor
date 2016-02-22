<?php

namespace Spatie\FailedJobsMonitor;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\ServiceProvider;
use Queue;


class FailedJobsMonitorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     * @param Schedule $schedule
     */
    public function boot(Schedule $schedule)
    {
        $this->publishes([
            __DIR__.'/../config/laravel-failed-jobs-monitor.php' => config_path('laravel-failed-jobs-monitor.php'),
        ], 'config');

        /*
        $this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/skeleton'),
        ], 'views');
        */

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-failed-jobs-monitor');

        $this->app->bind('command.failed-jobs:mail', FailedJobsMailCommand::class);

        $this->app->bind('command.failed-jobs:slack', FailedJobsSlackCommand::class);

        $this->commands(['command.failed-jobs:mail', 'command.failed-jobs:slack']);

        $this->bootNotifications($schedule);
    }

    public function bootNotifications($schedule){

        $config = config('laravel-failed-jobs-monitor.notifications');
        if($config['mail']['frequency'] != 'none') $this->app->make(FailedJobNotifier::class)->bootMailNotifications($schedule, $config['mail']);
        if($config['slack']['frequency'] != 'none') $this->app->make(FailedJobNotifier::class)->bootSlackNotifications($schedule, $config['slack']);
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-failed-jobs-monitor.php', 'laravel-failed-jobs-monitor');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'command.failed-jobs:mail',
            'command.failed-jobs:slack'
        ];
    }

}
