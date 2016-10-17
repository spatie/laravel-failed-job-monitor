<?php

namespace Spatie\FailedJobMonitor\Test;

use Illuminate\Queue\Events\JobFailed;
use Spatie\FailedJobMonitor\Notifiable;
use Spatie\FailedJobMonitor\Notification;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Spatie\FailedJobMonitor\Test\Dummy\Job;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use Spatie\FailedJobMonitor\Test\Dummy\AnotherNotifiable;
use Spatie\FailedJobMonitor\Test\Dummy\AnotherNotification;

class FailedJobMonitorTest extends TestCase
{
    use DatabaseMigrations;

    /** @var \Spatie\FailedJobMonitor\Test\Dummy\TestQueueManager */
    protected $manager;

    public function setUp()
    {
        parent::setUp();

        NotificationFacade::fake();
    }

    /** @test */
    public function it_can_send_notification_when_a_job_failed()
    {
        $this->fireFailedEvent();

        NotificationFacade::assertSentTo(new Notifiable(), Notification::class);
    }

    /** @test */
    public function it_can_send_notification_when_job_failed_to_different_notifiable()
    {
        $this->app['config']->set('laravel-failed-job-monitor.notifiable', AnotherNotifiable::class);

        $this->fireFailedEvent();

        NotificationFacade::assertSentTo(new AnotherNotifiable(), Notification::class);
    }

    /** @test */
    public function it_can_send_notification_when_job_failed_to_different_notification()
    {
        $this->app['config']->set('laravel-failed-job-monitor.notification', AnotherNotification::class);

        $this->fireFailedEvent();

        NotificationFacade::assertSentTo(new Notifiable(), AnotherNotification::class);
    }

    protected function fireFailedEvent()
    {
        return event(new JobFailed('test', new Job(), new \Exception()));
    }
}
