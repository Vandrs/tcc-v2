<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Business\ElasticExportBusiness;

class ExportUsersToElastic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:users-to-elastic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Exporta os usuários parar o elastic search';

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
        $this->info('Exportando os usuários...');
        $elasticExportBusiness = new ElasticExportBusiness;
        $elasticExportBusiness->setOutPut($this->output);
        $elasticExportBusiness->exportUsers();
        $this->info('');
        $this->info('Ação concluída visualizar relatório de logs para eventuais erros');
    }
}
