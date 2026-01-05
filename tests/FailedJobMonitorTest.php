<?php

use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use Spatie\FailedJobMonitor\Notifiable;
use Spatie\FailedJobMonitor\Notification;
use Spatie\FailedJobMonitor\Test\Dummy\AnotherNotifiable;
use Spatie\FailedJobMonitor\Test\Dummy\AnotherNotification;
use Spatie\FailedJobMonitor\Test\Dummy\Job;

beforeAll(function () {
    function fireFailedEvent()
    {
        return event(new JobFailed('test', new Job(), new \Exception()));
    }
    function returnsFalseWhenExceptionIsEmpty($notification)
    {
        $message = $notification->getEvent()->exception->getMessage();

        return ! empty($message);
    }
});

beforeEach(function () {
    NotificationFacade::fake();
});

it('can send notification when a job failed', function () {
    fireFailedEvent();

    NotificationFacade::assertSentTo(new Notifiable(), Notification::class);
});

it('can send notification when job failed to different notifiable', function () {
    config()->set('failed-job-monitor.notifiable', AnotherNotifiable::class);

    fireFailedEvent();

    NotificationFacade::assertSentTo(new AnotherNotifiable(), Notification::class);
});

it('can send notification when job failed to different notification', function () {
    config()->set('failed-job-monitor.notification', AnotherNotification::class);

    fireFailedEvent();

    NotificationFacade::assertSentTo(new Notifiable(), AnotherNotification::class);
});

it('filters out notifications when the notificationFilter returns `false`', function () {
    config()->set('failed-job-monitor.notificationFilter', 'returnsFalseWhenExceptionIsEmpty');

    fireFailedEvent();

    NotificationFacade::assertNotSentTo(new Notifiable(), Notification::class);
});
