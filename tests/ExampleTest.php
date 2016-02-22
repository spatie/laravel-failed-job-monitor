<?php

namespace Spatie\FailedJobsMonitor;


class ExampleTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [FailedJobsMonitorServiceProvider::class];
    }
}
