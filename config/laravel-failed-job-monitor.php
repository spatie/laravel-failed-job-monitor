<?php

return [

        /**
         *
         * The services that are wanted to be used to receive the notifications when a queued job fails
         * must be specified in the senders array.
         * More services can be added.
         *
         **/

        'senders' => ['mail', 'slack'],

        //these are mail notifications configurations
        'mail' => [
            'from' => 'your@email.com',
            'to' => 'your@email.com',
        ],

        //these are slack notifications configurations
        'slack' => [
            'channel' => '#failed-job',
            'username' => 'Failed Job Bot',
            'icon' => ':robot:',
        ],

        // if needed more services can be added here

];
