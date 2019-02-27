<?php

namespace Spatie\FailedJobMonitor\Test;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Spatie\FailedJobMonitor\FailedJobMonitorServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    use DatabaseMigrations;

    protected function getPackageProviders($app)
    {
        return [FailedJobMonitorServiceProvider::class];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('queue.default', 'sync');
        $app['config']->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite.database', ':memory:');
    }
}
