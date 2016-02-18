<?php

return [
    'notifications' => [

        'mail' => [
            'frequency' => 'daily', // immediately|hourly|daily|weekly
            'from' => 'your@email.com',
            'to' => 'your@email.com',
        ],

        'slack' => [
            'frequency' => 'immediately', // immediately|hourly|daily|weekly
            'channel'  => '#failed-jobs',
            'username' => 'Failed jobs bot',
            'icon'     => ':robot:',
        ],

    ],
];