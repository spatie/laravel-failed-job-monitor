<?php

namespace Spatie\FailedJobsMonitor;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Queue\Events\JobFailed;
use Queue;

class FailedJobNotifier
{
    protected $app;


    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function bootMailNotifications($schedule, $config)
    {
        if($config['frequency'] == 'immediately'){

            Queue::failing(function (JobFailed $event) use ($config)
            {
                $failedJob = FailedJob::getLatest();
                $this->sendMail($config, $failedJob);
            });
        }
        else {
            $schedule->command('failed-jobs:mail')->cron($config['frequency']);
        }

    }

    public function bootSlackNotifications($schedule, $config)
    {
        if($config['frequency'] == 'immediately'){

            Queue::failing(function (JobFailed $event) use ($config)
            {
                $failedJob = FailedJob::getLatest();
                $this->sendSlackMessage($config, $failedJob);
            });
        }
        else {
            $schedule->command('failed-job:slack')->cron($config['frequency']);
        }

    }

    public function sendFailedJobOverview($method)
    {
        $config = $config = config('laravel-failed-jobs-monitor.notifications');
        $failedJob = FailedJob::getAll();
        if($method == 'mail') $this->sendMail($config, $failedJob);
        if($method == 'slack') $this->sendSlackMessage($config, $failedJob);


    }

    public function sendMail($config, $failedJob)
    {
        $this->app->make('mailer')->send('laravel-failed-jobs-monitor::email',['failedJobs' => $failedJob], function ($m) use ($config) {

            $m
                ->from($config['from'])
                ->to($config['to'])
                ->subject('Some of your jobs failed.');

        });
    }

    public function sendSlackMessage($config, $failedJobs)
    {
        $message = 'Job failed: '.$failedJobs->command;

//        \Log::info(config('slack'));

        $this->app->make('maknz.slack')
            ->to($config['channel'])
            ->withIcon(':'.$config['icon'].':')
            ->send($message);

    }
}