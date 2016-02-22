<?php

namespace Spatie\FailedJobsMonitor;

use Illuminate\Console\Command;

class FailedJobsSlackCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'failed-jobs:slack';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command sends a slack notification.';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param FailedJobNotifier $failedJobNotifier
     * @return mixed
     */
    public function handle(FailedJobNotifier $failedJobNotifier)
    {
        $failedJobNotifier->sendFailedJobOverview();

    }

}
