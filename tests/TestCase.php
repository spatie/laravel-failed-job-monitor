<?php

namespace Spatie\FailedJobMonitor\Test;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Spatie\FailedJobMonitor\Test\Dummy\ServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('queue.default', 'sync');
        $app['config']->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite.database', ':memory:');

        $config = [
            'Spatie\FailedJobMonitor\Test\Dummy\Data\SecondJob' => [
                'notifiable'   => \Spatie\FailedJobMonitor\Test\Dummy\Models\User::class,
                'notification' => \Spatie\FailedJobMonitor\Notification::class,
                'via'          => ['mail'],
                'filter' => 'scopeCanBeNotifiedAboutFailedAnotherJobs'
            ],
            'Spatie\FailedJobMonitor\Test\Dummy\Data\AnotherJob' => [
                'notifiable'   => \Spatie\FailedJobMonitor\Test\Dummy\Models\User::class,
                'notification' => \Spatie\FailedJobMonitor\Test\Dummy\Notifications\AnotherNotification::class,
                'via'          => ['mail'],
                //'filter' => 'scopeCanBeNotifiedAboutFailedAnotherJobs'
            ],
            'Spatie\FailedJobMonitor\Test\Dummy\Data\TeamJob' => [
                'notifiable'   => \Spatie\FailedJobMonitor\Test\Dummy\Models\Team::class,
                'notification' => \Spatie\FailedJobMonitor\Test\Dummy\Notifications\TeamNotification::class,
                'via'          => ['mail'],
                //'filter' => 'scopeCanBeNotifiedAboutFailedAnotherJobs'
            ],
            '*'                                      => [
                'notifiable'   => \Spatie\FailedJobMonitor\Test\Dummy\Models\User::class,
                'notification' => \Spatie\FailedJobMonitor\Notification::class,
                'via'          => ['mail'],
                //'filter' => 'canBeNotifiedAboutFailedJobs'
            ],
        ];

        $app['config']->set('laravel-failed-job-monitor', $config);


    }
}
