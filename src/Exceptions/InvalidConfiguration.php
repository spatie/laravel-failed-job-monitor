<?php

namespace Spatie\FailedJobMonitor\Exceptions;

use Exception;
use Illuminate\Notifications\Notification;

class InvalidConfiguration extends Exception
{
    public static function notificationClassInvalid($className): self
    {
        return new self("Class {$className} is an invalid notification class. ".
            'A notification class must extend '.Notification::class);
    }
}
