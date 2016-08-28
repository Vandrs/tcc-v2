<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Business\MailSenderBusiness;
use App\Utils\Utils;


class SendEmailJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            MailSenderBusiness::send($this->data);
        } catch(\Exception $e){
            $msg = Utils::getExceptionFullMessage($e);
            echo $msg;
            throw new \Exception($e);
        }
    }

}
