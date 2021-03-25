<?php

namespace Spatie\FailedJobMonitor;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FailedJobMonitorServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-failed-job-monitor')
            ->hasConfigFile();
    }

    public function packageBooted(): void
    {
        $this->app->make(FailedJobNotifier::class)->register();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(FailedJobNotifier::class);
    }
}
