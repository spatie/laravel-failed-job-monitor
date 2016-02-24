<?php

return [

        // The services that will be used to receive the notifications when a queued job fails must be specified in the channels array.
        'channels' => ['mail'],

        //these are mail notifications configurations
        'mail' => [
            'from' => 'your@email.com',
            'to' => 'your@email.com',
        ],

        //these are slack notifications configurations
        'slack' => [
            'channel' => '#failed-jobs',
            'username' => 'Failed jobs bot',
            'icon' => ':robot:',
        ],

        // if needed more services can be added here

];
