<?php

namespace Spatie\FailedJobsMonitor\Test;

use Spatie\FailedJobsMonitor\SlackHandlerInterface;

class InMemorySlack implements SlackHandlerInterface
{
    public function send($channel, $message)
    {
        // TODO: Implement send() method.
    }
}
