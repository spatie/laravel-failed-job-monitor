<?php

namespace Spatie\FailedJobMonitor;

use Illuminate\Notifications\ChannelManager;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\QueueManager;
use Spatie\FailedJobMonitor\Exceptions\InvalidNotifiableException;
use Spatie\FailedJobMonitor\Exceptions\InvalidNotificationException;

class FailedJobNotifier
{
    public function register()
    {
        $mapping = collect(config('laravel-failed-job-monitor'));

        app(QueueManager::class)->failing(function (JobFailed $event) use ($mapping) {
            if ($mapping->has($event->job->resolveName())) {
                $this->handle($mapping->get($event->job->resolveName()), $event);
            }

            if ($mapping->has('*')) {
                $this->handle($mapping->get('*'), $event);
            }
        });
    }

    public function isValidNotificationClass($notificationClass):bool
    {
        return $notificationClass === Notification::class || is_subclass_of($notificationClass, Notification::class);
    }

    private function handle($map, $event)
    {
        $notifiable = new $map['notifiable'];
        $notificationClass = $map['notification'];
        $filterMethod = array_get($map, 'filter', 'canBeNotifiedAboutFailedJobs');

        if (! method_exists($notifiable, 'scope'.ucfirst($filterMethod))) {
            throw new InvalidNotifiableException(
                sprintf('Class %s have scope%s method', $map['notifiable'], ucfirst($filterMethod))
            );
        }

        if (! $this->isValidNotificationClass($notificationClass)) {
            throw new InvalidNotificationException(
                "Class {$notificationClass} must extend ".Notification::class
            );
        }

        $notifiables = $notifiable->newQuery()->$filterMethod()->get();
        $notification = new $notificationClass($event, array_get($map, 'via', []));

        app(ChannelManager::class)->send($notifiables, $notification);
    }
}
