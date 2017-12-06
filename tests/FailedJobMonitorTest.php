<?php

namespace Spatie\FailedJobMonitor\Test;

use Illuminate\Queue\Events\JobFailed;
use Spatie\FailedJobMonitor\Notifiable;
use Spatie\FailedJobMonitor\Notification;
use Spatie\FailedJobMonitor\Test\Dummy\Job;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Spatie\FailedJobMonitor\Test\Dummy\AnotherNotifiable;
use Spatie\FailedJobMonitor\Test\Dummy\AnotherNotification;
use Illuminate\Support\Facades\Notification as NotificationFacade;

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
        $this->app['config']->set('failed-job-monitor.notifiable', AnotherNotifiable::class);

        $this->fireFailedEvent();

        NotificationFacade::assertSentTo(new AnotherNotifiable(), Notification::class);
    }

    /** @test */
    public function it_can_send_notification_when_job_failed_to_different_notification()
    {
        $this->app['config']->set('failed-job-monitor.notification', AnotherNotification::class);

        $this->fireFailedEvent();

        NotificationFacade::assertSentTo(new Notifiable(), AnotherNotification::class);
    }

    /** @test */
    public function it_filters_out_notifications_when_the_notificationFilter_returns_false()
    {
        $this->app['config']->set('failed-job-monitor.callback', [$this, 'returnsFalseWhenExceptionIsEmpty']);

        $this->fireFailedEvent();

        NotificationFacade::assertNotSentTo(new Notifiable(), AnotherNotification::class);
    }

    /** @test */
    public function it_does_not_send_a_notification_when_killswith_is_on()
    {
        $this->app['config']->set('failed-job-monitor.killswitch', true);

        $this->fireFailedEvent();

        NotificationFacade::assertNothingSent();
    }

    /** @test */
    public function it_does_send_a_notification_when_killswith_is_off()
    {
        $this->app['config']->set('failed-job-monitor.killswitch', false);

        $this->fireFailedEvent();

        NotificationFacade::assertSentTo(new Notifiable(), Notification::class);
    }

    protected function fireFailedEvent()
    {
        return event(new JobFailed('test', new Job(), new \Exception()));
    }

    public function returnsFalseWhenExceptionIsEmpty($notification)
    {
        $message = $notification->getEvent()->exception->getMessage();

        return ! empty($message);
    }
}
