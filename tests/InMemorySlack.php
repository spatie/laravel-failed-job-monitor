<?php

namespace Spatie\FailedJobMonitor\Test;

use Spatie\FailedJobMonitor\SlackHandlerInterface;

class InMemorySlack implements SlackHandlerInterface
{
    public function send($channel, $message)
    {
        // TODO: Implement send() method.
    }
}
