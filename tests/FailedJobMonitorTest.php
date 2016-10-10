<?php

namespace Spatie\FailedJobMonitor\Test;

use Illuminate\Queue\Events\JobFailed;
use Spatie\FailedJobMonitor\Notifiable;
use Spatie\FailedJobMonitor\Notification;
use Spatie\FailedJobMonitor\Test\Dummy\TestException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Spatie\FailedJobMonitor\Test\Dummy\TestQueueManager;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use Spatie\FailedJobMonitor\Test\Dummy\Notifications\AnotherNotifiable;
use Spatie\FailedJobMonitor\Test\Dummy\Notifications\AnotherNotification;

class FailedJobMonitorTest extends TestCase
{
    use DatabaseMigrations;

    /** @var TestQueueManager */
    protected $manager;

    public function setUp()
    {
        parent::setUp();
        $this->manager = new TestQueueManager($this->app);
        NotificationFacade::fake();
    }

    /** @test */
    public function it_can_send_notification_when_event_failed()
    {
        $job = $this->manager->generateJobForEventListener(random_int(1, 100));
        $this->fireFailed($job);

        NotificationFacade::assertSentTo(new Notifiable(), Notification::class);
    }

    /** @test */
    public function it_can_send_notification_when_job_failed()
    {
        $job = $this->manager->generateJob(random_int(1, 100));
        $this->fireFailed($job);

        NotificationFacade::assertSentTo(new Notifiable(), Notification::class);
    }

    /** @test */
    public function it_can_send_notification_when_job_failed_to_different_notifiable()
    {
        $this->app['config']->set('laravel-failed-job-monitor.notifiable', AnotherNotifiable::class);

        $job = $this->manager->generateJob(random_int(1, 100));
        $this->fireFailed($job);

        NotificationFacade::assertSentTo(new AnotherNotifiable(), Notification::class);
    }

    /** @test */
    public function it_can_send_notification_when_job_failed_to_different_notification()
    {
        $this->app['config']->set('laravel-failed-job-monitor.notification', AnotherNotification::class);

        $job = $this->manager->generateJob(random_int(1, 100));
        $this->fireFailed($job);

        NotificationFacade::assertSentTo(new Notifiable(), AnotherNotification::class);
    }

    /** @test */
    public function it_can_send_notification_when_job_failed_to_different_channels()
    {
        $this->app['config']->set('laravel-failed-job-monitor.channels', ['mail', 'slack']);

        $job = $this->manager->generateJob(random_int(1, 100));
        $this->fireFailed($job);

        NotificationFacade::assertSentTo(
            new Notifiable(),
            Notification::class,
            function ($notification, $channels) {
                return count(array_diff($channels, ['mail', 'slack'])) === 0;
            }
        );
    }

    /** @test */
    public function it_can_take_default_channels_info_from_config()
    {
        $this->app['config']->set('laravel-failed-job-monitor.mail.to', 'johndoe@example.com');
        $this->app['config']->set('laravel-failed-job-monitor.slack.webhook_url', 'SLACK_URL');

        $notifiable = new Notifiable();

        $this->assertEquals('SLACK_URL', $notifiable->routeNotificationForSlack());
        $this->assertEquals('johndoe@example.com', $notifiable->routeNotificationForMail());
    }

    protected function fireFailed($event)
    {
        $e = new TestException();

        return event(new JobFailed('test', $event, $e));
    }
}
