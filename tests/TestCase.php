<?php

namespace Spatie\FailedJobsMonitor\Test;

use Spatie\FailedJobsMonitor\FailedJobsMonitorServiceProvider;
use Illuminate\Database\Schema\Blueprint;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [FailedJobsMonitorServiceProvider::class];
    }

    public function getEnvironmentSetUp($app)
    {

        $app['config']->set('queue.default', 'sync');


    }
    
}
