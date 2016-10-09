<?php

return [
    'notifiable'   => \Spatie\FailedJobMonitor\Notifiable::class,
    'notification' => \Spatie\FailedJobMonitor\Notification::class,
    'channels'     => ['mail', 'slack'],
    'routes'       => [
        'mail' => [
            'to' => 'your@mail',
        ],

        'slack' => [
            'webhook_url' => '',
        ],
    ],
];
