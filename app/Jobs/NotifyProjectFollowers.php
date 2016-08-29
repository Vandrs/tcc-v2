<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Business\ProjectEmailBusiness;


class NotifyProjectFollowers extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $project;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($project)
    {
        $this->project = $project;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $projectEmailBusiness = new ProjectEmailBusiness();
        $projectEmailBusiness->feedNotificationEmail($this->project);
    }

}
