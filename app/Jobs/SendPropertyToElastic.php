<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Business\ElasticExportBusiness;
use App\Models\DB\Project;
use DB;


class SendPropertyToElastic extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    private $project;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Project $project)
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
        DB::reconnect();
        $this->project->fresh();
        $exportBusiness = new ElasticExportBusiness;
        $exportBusiness->exportProject($this->project);
    }

    /**
     * @TODO
     * Implementar o que fazer quando a exportação falhar
     */
    public function failed(){
        
    }
}
