<?php
/**
 * Created by PhpStorm.
 * User: jolita_pabludo
 * Date: 22/02/16
 * Time: 17:09
 */

namespace Spatie\FailedJobsMonitor;


interface SlackHandlerInterface
{
    public function send($channel, $message);
}