<?php

namespace Spatie\FailedJobMonitor\Test\Dummy\Data;

class SecondJob
{
    public $jobId;

    public function __construct($jobId)
    {
        $this->jobId = $jobId;
    }
}
