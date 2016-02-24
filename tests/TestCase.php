<?php

namespace Spatie\FailedJobMonitor\Test;

use Spatie\FailedJobMonitor\FailedJobMonitorServiceProvider;
use Illuminate\Database\Schema\Blueprint;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{

    protected function getPackageProviders($app)
    {
        return [FailedJobMonitorServiceProvider::class];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('queue.default', 'sync');

        $app['config']->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');
    }

}
