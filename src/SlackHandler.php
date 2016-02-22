<?php
/**
 * Created by PhpStorm.
 * User: jolita_pabludo
 * Date: 22/02/16
 * Time: 17:08
 */

namespace Spatie\FailedJobsMonitor;


class SlackHandler implements SlackHandlerInterface
{
    public function send($channel, $message)
    {
        $this->slack
            ->to($config['channel'])
            ->withIcon(':'.$config['icon'].':')
            ->send($message);
    }
}