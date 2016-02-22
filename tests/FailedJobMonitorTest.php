<?php

namespace Spatie\FailedJobsMonitor\Test;

use Spatie\FailedJobsMonitor\FailedJobNotifier;

class FailedJobMonitorTest extends TestCase
{
    /** @var \Spatie\FailedJobsMonitor\Test\InMemoryMailer */
    protected $mailer;

    public function setUp()
    {
        parent::setUp();

        $this->mailer = new InMemoryMailer;

    }

    /**
     * @test
     */
    public function message_has_a_specified_subject()
    {
        $notifier = new FailedJobNotifier($this->mailer);
        $notifier->sendFailedJobOverview('mail');
        $this->assertTrue($this->mailer->hasMessageWithSubject('Some of your jobs failed.'));
    }



    /**
     * @test
     */
    public function message_has_content()
    {
        $notifier = new FailedJobNotifier($this->mailer);
        $notifier->sendFailedJobOverview('mail');
        $this->assertTrue($this->mailer->hasContent('SendReminderEmail'));

    }

}