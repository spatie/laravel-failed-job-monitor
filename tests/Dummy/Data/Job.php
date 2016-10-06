<?php

namespace Spatie\FailedJobMonitor\Test\Dummy\Data;

class Job
{
    public $jobId;

    public function __construct($jobId)
    {
        $this->jobId = $jobId;
    }
}
