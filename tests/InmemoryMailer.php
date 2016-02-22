<?php

namespace Spatie\FailedJobsMonitor\Test;

use Illuminate\Contracts\Mail\Mailer;

class InMemoryMailer implements Mailer
{
    private $messages;

    public function __construct()
    {
        $this->messages = collect();
    }

    public function send($template, array $data, $callback)
    {
        $message = new Message($template, $data);
        $callback($message);
        $this->messages[] = $message;
    }

    public function raw($text, $callback)
    {

    }

    public function failures()
    {

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

    public function hasContent($content)
    {

        foreach($this->messages as $message)
        {
//            dd(view($message->template, $message->data)->render());
            return str_contains(view($message->template, $message->data)->render(), $content);

        }

    }
}
