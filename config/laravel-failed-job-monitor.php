<?php

return [
    'notifiable' => \Spatie\FailedJobMonitor\Notifiable::class,
    'notification' => \Spatie\FailedJobMonitor\Notification::class,
    'channels'   => ['mail', 'slack'],
    'routes'     => [
        'mail' => [
            'to' => 'email@example.com',
        ],

        'slack' => [
            'webhook_url' => '',
            'channel' => '#failed-jobs',
            'username' => 'Failed Job Bot',
            'icon' => ':robot_face:',
        ],
    ],
];
