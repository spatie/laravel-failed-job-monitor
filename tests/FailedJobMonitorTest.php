<?php

namespace Spatie\FailedJobMonitor\Test;

use Illuminate\Queue\Events\JobFailed;
use Spatie\FailedJobMonitor\Notification;
use Spatie\FailedJobMonitor\Test\Dummy\Models\Team;
use Spatie\FailedJobMonitor\Test\Dummy\Models\User;
use Spatie\FailedJobMonitor\Test\Dummy\Data\TeamJob;
use Spatie\FailedJobMonitor\Test\Dummy\TestException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Spatie\FailedJobMonitor\Test\Dummy\Data\SecondJob;
use Spatie\FailedJobMonitor\Test\Dummy\Data\AnotherJob;
use Spatie\FailedJobMonitor\Test\Dummy\TestQueueManager;
use Spatie\FailedJobMonitor\Test\Dummy\Notifications\AnotherNotification;
use Spatie\FailedJobMonitor\Test\Dummy\Notifications\TeamNotification;

class FailedJobMonitorTest extends TestCase
{
    use DatabaseMigrations;

    /** @var TestQueueManager */
    protected $manager;

    public function setUp()
    {
        parent::setUp();
        $this->manager = new TestQueueManager($this->app);
        \Illuminate\Support\Facades\Notification::fake();
    }

    /** @test */
    public function it_can_send_notification_when_event_crashed()
    {
        $users = $this->createUsers(2);
        $teams = $this->createTeams(2);

        $job = $this->manager->generateJobForEventListener(random_int(1, 100));
        $this->fireFailed($job);

        \Illuminate\Support\Facades\Notification::assertSentTo(
            $users[0],
            Notification::class,
            function (Notification $notification, $channels) use ($job) {
                return $notification->failed->job->getRawBody() === $job->getRawBody();
            }
        );

        \Illuminate\Support\Facades\Notification::assertNotSentTo($users[1], Notification::class);

        $users->each(function ($user) {
            \Illuminate\Support\Facades\Notification::assertNotSentTo($user, AnotherNotification::class);
            \Illuminate\Support\Facades\Notification::assertNotSentTo($user, TeamNotification::class);
        });

        $teams->each(function ($team) {
            \Illuminate\Support\Facades\Notification::assertNotSentTo($team, Notification::class);
            \Illuminate\Support\Facades\Notification::assertNotSentTo($team, AnotherNotification::class);
            \Illuminate\Support\Facades\Notification::assertNotSentTo($team, TeamNotification::class);
        });
    }

    /** @test */
    public function it_can_send_notification_when_job_crashed()
    {
        $users = $this->createUsers(2);
        $teams = $this->createTeams(2);
        $job = $this->manager->generateJob(random_int(1, 100));
        $this->fireFailed($job);

        \Illuminate\Support\Facades\Notification::assertSentTo(
            $users[0],
            Notification::class,
            function (Notification $notification, $channels) use ($job) {
                return $notification->failed->job->getRawBody() === $job->getRawBody();
            }
        );

        \Illuminate\Support\Facades\Notification::assertNotSentTo(
            $users[1], Notification::class
        );

        $users->each(function ($user) {
            \Illuminate\Support\Facades\Notification::assertNotSentTo($user, AnotherNotification::class);
            \Illuminate\Support\Facades\Notification::assertNotSentTo($user, TeamNotification::class);
        });

        $teams->each(function ($team) {
            \Illuminate\Support\Facades\Notification::assertNotSentTo($team, Notification::class);
            \Illuminate\Support\Facades\Notification::assertNotSentTo($team, AnotherNotification::class);
            \Illuminate\Support\Facades\Notification::assertNotSentTo($team, TeamNotification::class);
        });
    }

    /** @test */
    public function it_can_send_notification_when_job_crashed_to_additional_channel_using_different_filter_scope()
    {
        $users = $this->createUsers(2);
        $teams = $this->createTeams(2);
        $job = $this->manager->generateJob(random_int(1, 100), SecondJob::class);
        $this->fireFailed($job);

        $users->each(function ($user) use ($job) {
            \Illuminate\Support\Facades\Notification::assertSentTo(
                $user,
                Notification::class,
                function (Notification $notification, $channels) use ($job) {
                    return $notification->failed->job->getRawBody() === $job->getRawBody();
                }
            );

            \Illuminate\Support\Facades\Notification::assertNotSentTo($user, AnotherNotification::class);
            \Illuminate\Support\Facades\Notification::assertNotSentTo($user, TeamNotification::class);
        });

        $teams->each(function ($team) {
            \Illuminate\Support\Facades\Notification::assertNotSentTo($team, Notification::class);
            \Illuminate\Support\Facades\Notification::assertNotSentTo($team, AnotherNotification::class);
            \Illuminate\Support\Facades\Notification::assertNotSentTo($team, TeamNotification::class);
        });
    }

    /** @test */
    public function it_can_send_notification_when_job_crashed_to_additional_channel_using_different_notification_class()
    {
        $users = $this->createUsers(2);
        $teams = $this->createTeams(2);
        $job = $this->manager->generateJob(random_int(1, 100), AnotherJob::class);
        $this->fireFailed($job);

        \Illuminate\Support\Facades\Notification::assertSentTo(
            $users[0],
            Notification::class,
            function (Notification $notification, $channels) use ($job) {
                return $notification->failed->job->getRawBody() === $job->getRawBody();
            }
        );

        \Illuminate\Support\Facades\Notification::assertNotSentTo($users[1], TeamNotification::class);


        \Illuminate\Support\Facades\Notification::assertSentTo(
            $users[0],
            AnotherNotification::class,
            function (Notification $notification, $channels) use ($job) {
                return $notification->failed->job->getRawBody() === $job->getRawBody();
            }
        );

        \Illuminate\Support\Facades\Notification::assertNotSentTo(
            $users[1], AnotherNotification::class
        );

        $users->each(function ($user) {
            \Illuminate\Support\Facades\Notification::assertNotSentTo($user, TeamNotification::class);
        });

        $teams->each(function ($team) {
            \Illuminate\Support\Facades\Notification::assertNotSentTo($team, Notification::class);
            \Illuminate\Support\Facades\Notification::assertNotSentTo($team, AnotherNotification::class);
            \Illuminate\Support\Facades\Notification::assertNotSentTo($team, TeamNotification::class);
        });
    }

    /** @test */
    public function it_can_send_notification_when_job_crashed_to_additional_channel_using_different_notifiable_class()
    {
        $users = $this->createUsers(2);
        $teams = $this->createTeams(2);
        $job = $this->manager->generateJob(random_int(1, 100), TeamJob::class);
        $this->fireFailed($job);

        \Illuminate\Support\Facades\Notification::assertSentTo(
            $users[0],
            Notification::class,
            function (Notification $notification, $channels) use ($job) {
                return $notification->failed->job->getRawBody() === $job->getRawBody();
            }
        );

        \Illuminate\Support\Facades\Notification::assertNotSentTo($users[1], TeamNotification::class);


        $users->each(function ($user) {
            \Illuminate\Support\Facades\Notification::assertNotSentTo($user, AnotherNotification::class);
            \Illuminate\Support\Facades\Notification::assertNotSentTo($user, TeamNotification::class);
        });

        $teams->each(function ($team) {
            \Illuminate\Support\Facades\Notification::assertNotSentTo($team, Notification::class);
            \Illuminate\Support\Facades\Notification::assertNotSentTo($team, AnotherNotification::class);
        });

        \Illuminate\Support\Facades\Notification::assertSentTo(
            $teams[0],
            TeamNotification::class,
            function (Notification $notification, $channels) use ($job) {
                return $notification->failed->job->getRawBody() === $job->getRawBody();
            }
        );

        \Illuminate\Support\Facades\Notification::assertNotSentTo($teams[1], TeamNotification::class);
    }

    protected function fireFailed($event)
    {
        $e = new TestException();

        return event(new JobFailed('test', $event, $e));
    }

    protected function createUsers($number = 1)
    {
        $data = collect(range(1, $number))->map(function () {
            return User::create([]);
        });

        if ($number == 1) {
            return $data->first();
        }

        return $data;
    }

    protected function createTeams($number = 1)
    {
        $data = collect(range(1, $number))->map(function () {
            return Team::create([]);
        });

        if ($number == 1) {
            return $data->first();
        }

        return $data;
    }
}
