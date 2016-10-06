<?php

return [
    '*' => [
        'notifiable' => \App\User::class,
        'notification' => \Spatie\FailedJobMonitor\Notification::class,
        'via' => ['mail', 'slack'],
        //'filter' => 'canBeNotifiedAboutFailedJobs'
    ],
];
