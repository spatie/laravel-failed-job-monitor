<?php

namespace Spatie\FailedJobMonitor\Senders;

interface Sender
{
    public function send(string $failedJobData);
}
