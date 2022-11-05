<?php

namespace Spatie\FailedJobMonitor;

use Illuminate\Notifications\Notifiable as NotifiableTrait;

class Notifiable
{
    use NotifiableTrait;

    public function routeNotificationForMail(): array
    {
        $recipients = config('failed-job-monitor.mail.to');

        if (is_string($recipients)) {
            return [$recipients];
        }

        return $recipients;
    }

    public function routeNotificationForSlack(): string
    {
        return config('failed-job-monitor.slack.webhook_url');
    }

    public function getKey(): int
    {
        return 1;
    }
}
