<?php

namespace Spatie\FailedJobsMonitor\Test;


class InMemoryMailer
{
    private $messages;

    public function __construct()
    {
        $this->messages = collect();
    }

    public function send($template, $data, $callback)
    {
        $message = new Message($template, $data);
        $callback($message);
        $this->messages[] = $message;
    }

    public function hasMessageFor($email)
    {
        return $this->messages->contains(function ($i, $message) use ($email) {
            return $message->to == $email;
        });
    }

    public function hasMessageWithSubject($subject)
    {
        return $this->messages->contains(function ($i, $message) use ($subject) {
            return $message->subject == $subject;
        });
    }
}
