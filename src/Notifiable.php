<?php

namespace Spatie\FailedJobMonitor;

use Illuminate\Notifications\Notifiable as NotifiableTrait;

class Notifiable
{
    use NotifiableTrait;

    public function routeNotificationForMail(): string
    {
        return config('failed-job-monitor.mail.to');
    }

    public function routeNotificationForSlack(): ?string
    {
        return config('failed-job-monitor.slack.webhook_url');
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return 1;
    }
}
