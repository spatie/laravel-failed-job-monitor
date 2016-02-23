<?php

return [

        'channels' => ['mail', 'slack'],

        'mail' => [
            'from' => 'your@email.com',
            'to' => 'your@email.com',
        ],

        'slack' => [
            'channel'  => '#failed-jobs',
            'username' => 'Failed jobs bot',
            'icon'     => ':robot:',
        ],

];