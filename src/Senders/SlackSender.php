<?php

namespace Spatie\FailedJobMonitor\Senders;

use Maknz\Slack\Client as Slack;
use Maknz\Slack\Attachment;

class SlackSender implements Sender
{
    protected $slack;

    public function __construct(Slack $slack)
    {
        $this->slack = $slack;
    }

    public function send(string $failedJobClassName, string $failedJobData)
    {
        $config = config('laravel-failed-job-monitor.slack');
        $message = 'Failed job found in '.url('');

        $attachment = new Attachment([
            'title' => 'The failing job class name is: '.$failedJobClassName,
            'text'  => $failedJobData,
            'color' => 'danger'
        ]);

        $this->slack
            ->to($config['channel'])
            ->from($config['username'])
            ->attach($attachment)
            ->withIcon($config['icon'])
            ->send($message);
    }
}
