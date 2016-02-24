<?php

namespace Spatie\FailedJobsMonitor\Test;

use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Support\Collection;

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

    public function hasMessageFor(string $email) : bool
    {
        return $this->messages->contains(function ($i, $message) use ($email) {
            return $message->to === $email;
        });
    }

    public function hasMessageWithSubject(string $subject) : bool
    {
        return $this->messages->contains(function ($i, $message) use ($subject) {
            return $message->subject === $subject;
        });
    }

    public function hasContent(string $content, Message $message) : bool
    {
        return str_contains($this->renderMessage($message), $content);
    }

    public function getMessages() : Collection
    {
        return $this->messages;
    }

    protected function renderMessage(Message $message) : string
    {
        return view($message->template, $message->data)->render();
    }
}
