<?php

namespace Spatie\FailedJobsMonitor;


abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase($this->app);
    }


    protected function getPackageProviders()
    {
        return [FailedJobsMonitorServiceProvider::class];
    }

    public function  getEnvironmentSetUp($app)
    {

        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => $this->getTempDirectory().'/database.sqlite',
            'prefix' => '',
        ]);

    }


    protected function setUpDatabase($app)
    {
        file_put_contents($this->getTempDirectory().'/database.sqlite', null);

        $app['db']->connection()->getSchemaBuilder()->create('test_models', function (Blueprint $table) {
            $table->increments('id');
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->timestamp('failed_at');
        });

    }
}
