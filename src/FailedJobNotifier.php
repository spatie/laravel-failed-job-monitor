<?php

namespace Spatie\FailedJobMonitor;

use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\QueueManager;
use Spatie\FailedJobMonitor\Senders\Sender;

class FailedJobNotifier
{
    public function notifyIfJobFailed(string $sender)
    {
        $sender = $this->getChannelInstance($sender);

        app(QueueManager::class)->failing(function (JobFailed $event) use ($sender) {

            $sender->send(json_encode($event->job->payload()));
        });
    }

    protected function getChannelInstance(string $sender) : Sender
    {
        $className = '\Spatie\FailedJobMonitor\Senders\\'.ucfirst($sender).'Sender';

        return app($className);
    }
}
