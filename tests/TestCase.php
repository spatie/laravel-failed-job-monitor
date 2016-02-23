<?php

namespace Spatie\FailedJobsMonitor\Test;

use Carbon\Carbon;
use Spatie\FailedJobsMonitor\FailedJob;
use Spatie\FailedJobsMonitor\FailedJobsMonitorServiceProvider;
use Illuminate\Database\Schema\Blueprint;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase($this->app);
    }


    protected function getPackageProviders($app)
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

        $app['config']->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');

    }


    protected function setUpDatabase($app)
    {
        file_put_contents($this->getTempDirectory().'/database.sqlite', null);

        $app['db']->connection()->getSchemaBuilder()->create('failed_jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->timestamp('failed_at');


        });

        foreach (range(1,4) as $index){

            FailedJob::create([
                'connection'    =>  'beanstalkd',
                'queue'         =>  'default',
                'payload'       =>  '{"job":"Illuminate\\Queue\\CallQueuedHandler@call","data":{"command":"O:26:\"App\\Jobs\\SendReminderEmail\":5:{s:7:\"\u0000*\u0000user\";O:45:\"Illuminate\\Contracts\\Database\\ModelIdentifier\":2:{s:5:\"class\";s:15:\"App\\Models\\User\";s:2:\"id\";i:6;}s:6:\"\u0000*\u0000job\";N;s:10:\"connection\";N;s:5:\"queue\";N;s:5:\"delay\";N;}"}}',
                'failed_at'     =>  Carbon::now()
            ]);
        }

        FailedJob::create([
            'connection'    =>  'beanstalkd',
            'queue'         =>  'default',
            'payload'       =>  '',
            'failed_at'     =>  Carbon::now()
        ]);

    }

    public function getTempDirectory($suffix = '')
    {
        return __DIR__.'/temp'.($suffix == '' ? '' : '/'.$suffix);
    }
}
