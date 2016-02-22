<?php

return [
    'notifications' => [

        'mail' => [
            'frequency' => 'immediately', // none/immediately|hourly|daily|weekly
            'from' => 'your@email.com',
            'to' => 'your@email.com',
        ],

        'slack' => [
            'frequency' => 'none', // none/immediately|hourly|daily|weekly
            'channel'  => '#failed-jobs',
            'username' => 'Failed jobs bot',
            'icon'     => ':robot:',
        ],

    ],
];