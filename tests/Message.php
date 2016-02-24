<?php

namespace Spatie\FailedJobsMonitor\Test;

class Message
{
    public $template;
    public $data;
    public $from;
    public $subject;

    public function __construct($template, $data)
    {
        $this->template = $template;
        $this->data = $data;
    }

    public function to($email)
    {
        $this->to = $email;

        return $this;
    }

    public function from($email)
    {
        $this->to = $email;

        return $this;
    }

    public function subject($subject)
    {
        $this->subject = $subject;

        return $this;
    }
}
