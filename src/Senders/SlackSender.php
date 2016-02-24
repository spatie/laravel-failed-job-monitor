<?php

namespace Spatie\FailedJobMonitor\Senders;

use Maknz\Slack\Client as Slack;

class SlackSender implements Sender
{
    protected $slack;

    public function __construct(Slack $slack)
    {
        $this->slack = $slack;
    }

    public function send(string $failedJobClassName, string $failedJobData)
    {
        $config = config('laravel-failed-jobs-monitor.slack');
        $message = 'Job failed: '.$failedJobClassName;

        $attachment = new Attachment([
            'fallback' => 'Some fallback text',
            'text' => 'The attachment text'
        ]);

        $this->slack
            ->to($config['channel'])
            ->attach($attachment)
            ->withIcon(':'.$config['icon'].':')
            ->send($message);
    }
}
