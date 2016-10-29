<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Utils\Utils;
use Log;
use App\Models\Business\ProjectValidationBusiness;
use App\Models\Business\ProjectEmailBusiness;

class SendValidationEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-validation-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verica se algum projeto entrou em período de avaliação para notificar os seus seguidores';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $this->info('Vericando validações para notificar os usuários');
            $validationBusiness = new ProjectValidationBusiness();
            $emailBusiness = new ProjectEmailBusiness();
            $validations = $validationBusiness->getValidationsToNotify();
            $this->info('Foram encontradas '.$validations->count().' validações');
            $validations->each(function($validation) use ($emailBusiness) {
                $emailBusiness->feedValidationNotificationEmail($validation);
                $validation->update(['notified' => 1]);
            });
            $this->info('Processo finalizado!');
        } catch (\Exception $e) {
            $errorMsg = Utils::getExceptionFullMessage($e);
            Log::error($errorMsg);
            $this->error($errorMsg);
        }
        
    }
}
