<?php

namespace Spatie\FailedJobMonitor\Test\Dummy;

use Illuminate\Contracts\Queue\Job;
use Illuminate\Queue\Jobs\SyncJob;

class TestQueueManager
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    protected function createPayload($job, $data = '', $queue = null) : string
    {
        $data = $this->generatePayload($job, $data);

        if (json_last_error() !== JSON_ERROR_NONE ) {
            throw new \InvalidArgumentException('Unable to create payload: '.json_last_error_msg());
        }

        return $data;
    }

    protected function generatePayload($job, $data = '')
    {
        if (is_object($job)) {
            return json_encode([
                'job' => 'Illuminate\Queue\CallQueuedHandler@call',
                'data' => [
                    'commandName' => get_class($job),
                    'command' => serialize(clone $job),
                ],
            ]);
        }

        return json_encode($this->createPlainPayload($job, $data));
    }

    /**
     * Create a typical, "plain" queue payload array.
     *
     * @param  string  $job
     * @param  mixed  $data
     * @return array
     */
    protected function createPlainPayload($job, $data) : array
    {
        return ['job' => $job, 'data' => $data];
    }

    /**
     * Resolve a Sync job instance.
     *
     * @param  string  $payload
     * @param  string  $queue
     * @return \Illuminate\Queue\Jobs\SyncJob
     */
    protected function resolveJob($payload, $queue) : Job
    {
        return new SyncJob($this->container, $payload, $queue);
    }

    public function generateJobForEventListener($eventId) : Job
    {
        return $this->make('Illuminate\Events\CallQueuedHandler@call', [
            'class' => 'Spatie\FailedJobMonitor\Test\Dummy\Listener', 'method' => 'handle', 'data' => serialize(['id' => $eventId]),
        ]);
    }

    public function generateJob($jobId, $jobName = 'Spatie\FailedJobMonitor\Test\Dummy\Data\Job') : Job
    {
        $job = new $jobName($jobId);

        return $this->make($job);
    }

    private function make($job, $data = '', $queue = null) : Job
    {
        return $this->resolveJob($this->createPayload($job, $data, $queue), $queue);
    }
}
