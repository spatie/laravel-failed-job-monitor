<?php

namespace Spatie\FailedJobMonitor;

use Illuminate\Notifications\Notifiable as NotifiableTrait;

class Notifiable
{
    use NotifiableTrait;

    /**
     * Route notifications for the mail channel.
     *
     * @return string
     */
    public function routeNotificationForMail()
    {
        return config('laravel-failed-job-monitor.routes.mail.to');
    }

    public function routeNotificationForSlack()
    {
        return config('laravel-failed-job-monitor.routes.slack.webhook_url');
    }

    public function getKey()
    {
        return 1;
    }
}
