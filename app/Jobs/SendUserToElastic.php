<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Business\ElasticExportBusiness;
use App\Models\DB\User;
use DB;


class SendUserToElastic extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    private $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::reconnect();
        $this->user->fresh();
        $exportBusiness = new ElasticExportBusiness;
        $exportBusiness->exportUser($this->user);
    }

    /**
     * @TODO
     * Implementar o que fazer quando a exportação falhar
     */
    public function failed(){
        
    }
}
