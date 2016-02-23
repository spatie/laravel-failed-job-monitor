<?php

namespace Spatie\FailedJobsMonitor\Channels;


class SlackChannel implements Channel
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function send(string $failedJob)
    {
        $config = config('laravel-failed-jobs-monitor.mail');
        $message = 'Job failed: '.$failedJob;

//        \Log::info(config('slack'));

        $this->app->make('maknz.slack')
            ->to($config['channel'])
            ->withIcon(':'.$config['icon'].':')
            ->send($message);

    }

}