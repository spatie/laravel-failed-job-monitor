<?php

namespace Spatie\FailedJobsMonitor\Channels;

use Illuminate\Support\Facades\Mail;

class MailChannel implements Channel
{
    public function send(string $failedJob)
    {
        $config = config('laravel-failed-jobs-monitor.mail');

        Mail::send('laravel-failed-jobs-monitor::email',['failedJob' => $failedJob], function ($m) use ($config) {

            $m
                ->from($config['from'])
                ->to($config['to'])
                ->subject('Job failed.');

        });
    }

}