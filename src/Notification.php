<?php

namespace Spatie\FailedJobMonitor;

use Illuminate\Queue\Events\JobFailed;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Notification as IlluminateNotification;

class Notification extends IlluminateNotification
{
    /** @var \Illuminate\Queue\Events\JobFailed */
    protected $event;

    public function via($notifiable): array
    {
        return config('laravel-failed-job-monitor.channels');
    }

    public function setEvent(JobFailed $event): self
    {
        $this->event = $event;

        return $this;
    }
    
    public function getEvent(): JobFailed
    {
        return $this->event;
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->error()
            ->subject('A job failed at '.config('app.url'))
            ->line("Exception message: {$this->event->exception->getMessage()}")
            ->line("Job class: {$this->event->job->resolveName()}")
            ->line("Job body: {$this->event->job->getRawBody()}")
            ->line("Exception: {$this->event->exception->getTraceAsString()}");
    }

    public function toSlack(): SlackMessage
    {
        return (new SlackMessage)
            ->error()
            ->content('A job failed at '.config('app.url'))
            ->attachment(function (SlackAttachment $attachment) {
                $attachment->fields([
                    'Exception message' => $this->event->exception->getMessage(),
                    'Job class' => $this->event->job->resolveName(),
                    'Job body' => $this->event->job->getRawBody(),
                    'Exception' => $this->event->exception->getTraceAsString(),
                ]);
            });
    }
}
