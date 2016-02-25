<?php

return [

    /**
     * If a jobs fails we will send you a notification via these channels.
     * You can use "mail", "slack" or both.
     */
    'senders' => ['mail'],

    'mail' => [
        'from' => 'your@email.com',
        'to' => 'your@email.com',
    ],

    /**
     * If want to send notifications to slack you must
     * install the "maknz/slack" package
     */
    'slack' => [
        'channel' => '#failed-jobs',
        'username' => 'Failed Job Bot',
        'icon' => ':robot_face:',
    ],
];
