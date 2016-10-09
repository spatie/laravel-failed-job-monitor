<?php

namespace Spatie\FailedJobMonitor;

use Illuminate\Notifications\Notifiable as NotifiableTrait;

class Notifiable
{
    use NotifiableTrait;

    public function routeNotificationForMail(): string
    {
        return config('laravel-failed-job-monitor.routes.mail.to');
    }

    public function routeNotificationForSlack(): string
    {
        return config('laravel-failed-job-monitor.routes.slack.webhook_url');
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return 1;
    }
}
