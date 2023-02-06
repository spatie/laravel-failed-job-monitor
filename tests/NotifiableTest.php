<?php

use Spatie\FailedJobMonitor\Notifiable;

it('returns always list of emails', function () {
    $notifiable = new Notifiable();

    config()->set('failed-job-monitor.mail.to', 'example@gmail.com');
    expect($notifiable->routeNotificationForMail())->toEqual(['example@gmail.com']);

    $recipients = ['example@gmail.com', 'example2@gmail.com'];
    config()->set('failed-job-monitor.mail.to', $recipients);
    expect($notifiable->routeNotificationForMail())->toEqual($recipients);
});
