<?php

namespace Spatie\FailedJobMonitor\Exceptions;

use Exception;

class InvalidConfiguration extends Exception
{
    public static function notificationClassInvalid($className): self
    {
        return  new static("Class {$className} is an invalid notification class. A notification class must extend " . Notification::class);
    }

}
