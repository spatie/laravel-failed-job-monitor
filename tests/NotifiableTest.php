<?php

namespace Spatie\FailedJobMonitor\Test;

use Spatie\FailedJobMonitor\Notifiable;

class NotifiableTest extends TestCase
{
    /** @test */
    public function it_returns_always_list_of_emails()
    {
        $notifiable = new Notifiable();

        $this->app['config']->set('failed-job-monitor.mail.to', 'example@gmail.com');
        $this->assertEquals(['example@gmail.com'], $notifiable->routeNotificationForMail());

        $recipients = ['example@gmail.com', 'example2@gmail.com'];
        $this->app['config']->set('failed-job-monitor.mail.to', $recipients);
        $this->assertEquals($recipients, $notifiable->routeNotificationForMail());
    }
}
