<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Jobs\Job;
use Carbon\Carbon;

class UpdateJobStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update job status after deadline';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();

        Job::whereRaw("deadline + INTERVAL 18 HOUR < ?", [$now])
            ->where('status', 'Published')
            ->update(['status' => 'Expired']);

        $this->info('Job statuses updated after 18:00 of deadline.');
    }
}
