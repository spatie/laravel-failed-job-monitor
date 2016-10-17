<?php

namespace Spatie\FailedJobMonitor\Test\Dummy;

use Illuminate\Notifications\Notifiable as NotifiableTrait;

class AnotherNotifiable
{
    use NotifiableTrait;

    public function routeNotificationForMail()
    {
        return 'john@example.com';
    }

    public function routeNotificationForSlack()
    {
        return '';
    }

    public function getKey()
    {
        return 1;
    }
}
