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
        $this->app->instance('mailer', $this->mailer);

        dd($this->mailer);
    }

    /**
     * @test
     */
    public function testExample()
    {
        $notifier = new FailedJobNotifier($this->mailer);
        $notifier->sendFailedJobOverview('mail');
    }

    /**
     * @test
     */
    public function message_has_a_specified_subject()
    {
        $this->assertTrue($this->mailer->hasMessageWithSubject('Some of your jobs failed.'));
    }

    public function message_has_content()
    {
        $this->assertTrue(true);

    }

}