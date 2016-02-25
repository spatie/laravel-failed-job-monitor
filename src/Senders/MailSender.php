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
        $config = config('laravel-failed-job-monitor.mail');

        $viewName = 'laravel-failed-job-monitor::email';

        $data = [
            'failedJobClassName' => $failedJobClassName,
            'failedJobData' => $failedJobData
        ];

        $this->mailer->send($viewName, $data, function ($message) use ($config) {
            $message
                ->from($config['from'])
                ->to($config['to'])
                ->subject('A queued job has failed on '.config('app.url'));
        });
    }
}
