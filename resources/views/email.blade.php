<h1>
    One of the queued jobs failed !
</h1>

<div>
    Here is the failed job class name: {{ $failedJobClassName }}
</div>
<div>

    The failed job details:
    {{ print_r($failedJobData) }}
</div>