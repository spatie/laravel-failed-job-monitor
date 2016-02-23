<?php

namespace Spatie\FailedJobsMonitor;

use Illuminate\Queue\Events\JobFailed;
use Queue;
use Spatie\FailedJobsMonitor\Channels\Channel;

class FailedJobNotifier
{

    public function notifyIfJobFailed(string $channel)
    {
        $channel = $this->getChannelInstance($channel);

        Queue::failing(function (JobFailed $event) use ($channel)
        {
            $channel->send($this->getJobName($event));
        });

    }

    protected function getJobName(JobFailed $event) : string
    {
        return get_class(unserialize($event));
    }

    protected function getChannelInstance(string $channel) : Channel
    {
        $className = '\Spatie\FailedJobsMonitor\Channels\\'.ucfirst($channel).'Channel';

        return new $className;
    }

}