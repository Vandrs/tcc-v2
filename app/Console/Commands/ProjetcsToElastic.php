<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Business\ElasticExportBusiness;

class ProjetcsToElastic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:projects-to-elastic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Exporta os projetos para o Elasticsearch';

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
        $this->info('Exportando os projetos...');
        $elasticExportBusiness = new ElasticExportBusiness;
        $elasticExportBusiness->setOutPut($this->output);
        $elasticExportBusiness->exportProjects();
        $this->info('');
        $this->info('Ação concluída visualizar relatório de logs para eventuais erros');
    }
}
