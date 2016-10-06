<?php

namespace Spatie\FailedJobMonitor\Test\Dummy;

use Spatie\FailedJobMonitor\ServiceProvider as ServiceProviderBase;

class ServiceProvider extends ServiceProviderBase
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        parent::boot();

        if($this->app->environment('testing'))
        {
            $this->loadMigrationsFrom(__DIR__ . '/../migrations');
        }
    }
}
