<?php

namespace Spatie\FailedJobsMonitor\Test;

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
        $this->notifier = new FailedJobNotifier($this->mailer);
    }

    /**
     * @test
     */
    public function message_has_a_specified_subject()
    {
        $this->notifier->sendFailedJobOverview('mail');

        $this->assertTrue($this->mailer->hasMessageWithSubject('Jobs failed.'));
    }

    /**
     * @test
     */
    public function message_has_content()
    {
        $this->notifier->sendFailedJobOverview('mail');

        $contains = false;

        foreach($this->mailer->getMessages() as $message){

            if ($this->mailer->hasContent('SendReminderEmail', $message)) {
                $contains = true;
            }
        }

        $this->assertTrue($contains);
    }

}