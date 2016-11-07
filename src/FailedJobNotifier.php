<?php

namespace Spatie\FailedJobMonitor;

use Illuminate\Queue\QueueManager;
use Illuminate\Queue\Events\JobFailed;
use Spatie\FailedJobMonitor\Exceptions\InvalidConfiguration;

class FailedJobNotifier
{
    public function register()
    {
        app(QueueManager::class)->failing(function (JobFailed $event) {
            $notifiable = app(config('laravel-failed-job-monitor.notifiable'));

            $notification = app(config('laravel-failed-job-monitor.notification'))->setEvent($event);

            if (! $this->isValidNotificationClass($notification)) {
                throw InvalidConfiguration::notificationClassInvalid(get_class($notification));
            }

            if ($this->shouldSendNotification($notification)) {
                $notifiable->notify($notification);
            }
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

    public function shouldSendNotification($notification)
    {
        $callable = config('laravel-failed-job-monitor.notificationFilter');

        if (! is_callable($callable)) {
            return true;
        }

        return $callable($notification);
    }
}
