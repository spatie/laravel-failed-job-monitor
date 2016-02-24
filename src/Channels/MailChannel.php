<?php

namespace Spatie\FailedJobsMonitor\Channels;

use Illuminate\Contracts\Mail\Mailer;

class MailChannel implements Channel
{
    protected $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send(string $failedJobClassName)
    {
        $config = config('laravel-failed-jobs-monitor.mail');

        $this->mailer->send('laravel-failed-jobs-monitor::email', ['failedJobClassName' => $failedJobClassName], function ($message) use ($config) {

            $message
                ->from($config['from'])
                ->to($config['to'])
                ->subject('Job failed.');

        });
    }
}
