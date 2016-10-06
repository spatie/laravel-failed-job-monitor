<?php

namespace Spatie\FailedJobMonitor;

use Carbon\Carbon;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification as NotificationBase;
use Illuminate\Queue\Events\JobFailed;

class Notification extends NotificationBase
{
    public $failed;

    public $via;

    public function __construct(JobFailed $failed, $via = [])
    {
        $this->failed = $failed;
        $this->via = $via;
    }

    public function via($notifiable)
    {
        return $this->via;
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
            ->line(trans('laravel-failed-job-monitor::mail.job_info', ['job' => $this->failed->job->resolveName()]))
            ->line(trans('laravel-failed-job-monitor::mail.attachment'))
            ->attachData($this->buildException($this->failed->exception),
                'failed_job_'.Carbon::now()->format('Y-m-d h:i:s').'.txt')
            ->attachData($this->failed->job->getRawBody(),
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
            ->attachment(function (SlackAttachment $attachment) {
                $attachment->title(trans('laravel-failed-job-monitor::slack.job_info'))
                    ->content($this->failed->job->resolveName());
            })->attachment(function (SlackAttachment $attachment) {
                $attachment->title('failed_job_'.Carbon::now()->format('Y-m-d h:i:s').'.txt')
                    ->content($this->buildException($this->failed->exception));
            })->attachment(function (SlackAttachment $attachment) {
                $attachment->title('payload_'.Carbon::now()->format('Y-m-d h:i:s').'.txt')
                    ->content($this->failed->job->getRawBody());
            });
    }

    protected function buildException(\Exception $e)
    {
        $response = '';
        while ($e !== null) {
            $response .= sprintf('%s: %s (%s:%s)', get_class($e), $e->getMessage(), $e->getFile(), $e->getLine());
            $response .= PHP_EOL;
            $response .= $e->getTraceAsString().PHP_EOL;
            $response .= '---------------------------'.PHP_EOL.PHP_EOL;
            $e = $e->getPrevious();
        }

        return $response;
    }
}
