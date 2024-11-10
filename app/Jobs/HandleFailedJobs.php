<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class HandleFailedJobs implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected $failedJob)
    {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        throw new \Exception('dead letter failed .');
    }
}
