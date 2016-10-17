<?php

namespace Spatie\FailedJobMonitor;

use Illuminate\Queue\QueueManager;
use Illuminate\Queue\Events\JobFailed;
use Spatie\FailedJobMonitor\Exceptions\InvalidConfiguration;
use Spatie\FailedJobMonitor\Exceptions\InvalidNotification;

class FailedJobNotifier
{
    public function register()
    {
        app(QueueManager::class)->failing(function (JobFailed $event) {
            $notifiable = app(config('laravel-failed-job-monitor.notifiable'));

            $notification = app(config('laravel-failed-job-monitor.notification'))->setEvent($event);

            if (!$this->isValidNotificationClass($notification)) {
                throw InvalidConfiguration::notificationClassInvalid(get_class($notification));
            }
            $notifiable->notify($notification);
        });
    }

    public function isValidNotificationClass($notification): bool
    {
        if (get_class($notification) === Notification::class) {
            return true;
        }

        if (is_subclass_of($notification, Notification::class)) {
            return true;
        }

        return false;
    }
}
