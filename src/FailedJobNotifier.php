<?php

namespace Spatie\FailedJobMonitor;

use Illuminate\Queue\QueueManager;
use Illuminate\Queue\Events\JobFailed;
use Spatie\FailedJobMonitor\Exceptions\InvalidNotificationException;

class FailedJobNotifier
{
    public function register()
    {
        app(QueueManager::class)->failing(function (JobFailed $event) {
            $notifiable = app(config('laravel-failed-job-monitor.notifiable'));
            $notification = app(config('laravel-failed-job-monitor.notification'))->setEvent($event);

            if (!$this->isValidNotificationClass($notification)) {
                throw new InvalidNotificationException(
                    "Class {get_class($notification)} must extend " . Notification::class
                );
            }

            $notifiable->notify($notification);
        });

    }

    public function isValidNotificationClass($notification):bool
    {
        return get_class($notification) === Notification::class || is_subclass_of($notification, Notification::class);
    }
}
