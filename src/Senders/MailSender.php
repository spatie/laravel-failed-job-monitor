<?php

namespace Spatie\FailedJobMonitor\Senders;

use Illuminate\Contracts\Mail\Mailer;

class MailSender implements Sender
{
    protected $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send(string $failedJobClassName, string $failedJobData)
    {
        $config = config('laravel-failed-jobs-monitor.mail');

        $this->mailer->send('laravel-failed-jobs-monitor::email', ['failedJobClassName' => $failedJobClassName, 'failedJobData' => $failedJobData], function ($message) use ($config) {

            $message
                ->from($config['from'])
                ->to($config['to'])
                ->subject('Job failed.');

        });
    }
}
