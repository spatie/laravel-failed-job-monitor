<?php

namespace Spatie\FailedJobMonitor;

use Carbon\Carbon;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Notification as NotificationBase;

class Notification extends NotificationBase
{
    protected $event;

    public function via($notifiable)
    {
        return config('laravel-failed-job-monitor.channels');
    }

    public function setEvent(JobFailed $event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(trans('laravel-failed-job-monitor::mail.subject'))
            ->greeting(trans('laravel-failed-job-monitor::mail.greeting'))
            ->line(trans('laravel-failed-job-monitor::mail.intro'))
            ->line(trans('laravel-failed-job-monitor::mail.job_info', ['job' => $this->event->job->resolveName()]))
            ->line(trans('laravel-failed-job-monitor::mail.attachment'))
            ->attachData($this->buildException($this->event->exception),
                'failed_job_'.Carbon::now()->format('Y-m-d h:i:s').'.txt')
            ->attachData($this->event->job->getRawBody(),
                'payload_'.Carbon::now()->format('Y-m-d h:i:s').'.txt');
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->error()
            ->content(trans('laravel-failed-job-monitor::slack.intro'))
            ->from(
                config('laravel-failed-job-monitor.routes.slack.username'),
                config('laravel-failed-job-monitor.routes.slack.icon')
            )
            ->to(config('laravel-failed-job-monitor.routes.slack.channel'))
            ->attachment(function (SlackAttachment $attachment) {
                $attachment->title(trans('laravel-failed-job-monitor::slack.job_info'))
                    ->content($this->event->job->resolveName());
            })->attachment(function (SlackAttachment $attachment) {
                $attachment->title('failed_job_'.Carbon::now()->format('Y-m-d h:i:s').'.txt')
                    ->content($this->buildException($this->event->exception));
            })->attachment(function (SlackAttachment $attachment) {
                $attachment->title('payload_'.Carbon::now()->format('Y-m-d h:i:s').'.txt')
                    ->content($this->event->job->getRawBody());
            });
    }

    protected function buildException(\Exception $exception)
    {
        $response = sprintf(
            '%s: %s (%s:%s)',
            get_class($exception),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine()
        );

        $response .= PHP_EOL;
        $response .= $exception->getTraceAsString().PHP_EOL;

        return $response;
    }
}
