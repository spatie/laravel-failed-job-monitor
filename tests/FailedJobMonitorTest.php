<?php

namespace Spatie\FailedJobsMonitor\Test;

use Illuminate\Contracts\Queue\Job;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Jobs\SyncJob;
use Spatie\FailedJobsMonitor\FailedJobNotifier;

class FailedJobMonitorTest extends TestCase
{
    /** @var \Spatie\FailedJobsMonitor\Test\InMemoryMailer */
    protected $mailer;

    /** @var  \Spatie\FailedJobsMonitor\FailedJobNotifier */
    protected $notifier;

    public function setUp()
    {
        parent::setUp();

        $this->mailer = new InMemoryMailer();
        $this->notifier = new FailedJobNotifier();

        $this->app->instance('mailer', $this->mailer);
    }

    /**
     * @test
     */
    public function message_has_a_specified_mail_address()
    {
        $this->notifier->notifyIfJobFailed('mail');

        $this->generateEvent();

        $this->assertTrue($this->mailer->hasMessageFor('your@email.com'));
    }

    /**
     * @test
     */
    public function message_has_a_specified_subject()
    {
        $this->notifier->notifyIfJobFailed('mail');

        $this->generateEvent();

        $this->assertTrue($this->mailer->hasMessageWithSubject('Job failed.'));
    }

    /**
     * @test
     */
    public function message_has_content()
    {
        $this->notifier->notifyIfJobFailed('mail');

        $this->generateEvent();

        $contains = false;

        foreach ($this->mailer->getMessages() as $message) {
            if ($this->mailer->hasContent('DummyEvent', $message)) {
                $contains = true;
            }
        }

        $this->assertTrue($contains);
    }

    /**
     * @test
     */
    public function slack_message_has_content()
    {
    }

    protected function getDummyJob() : Job
    {
        return new SyncJob($this->app, '');
    }

    protected function generateEvent() :array
    {
        return event(new JobFailed('test', $this->getDummyJob(),
            [
                'data' => [
                    'command' => serialize(new DummyEvent()),
                ],
            ]

        ));
    }
}
