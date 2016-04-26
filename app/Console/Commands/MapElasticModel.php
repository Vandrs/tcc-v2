<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Elastic\ElasticSearch;
use App\Utils\Benchmark;

class MapElasticModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:map-elastic-models';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refaz o mapeamento das models no Elasticsearch';

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
        $this->info('Mapeando Indices...');
        $benchmark = new Benchmark;
        $benchmark->start();
        $elasticsearch = new ElasticSearch;
        $elasticsearch->mapAllTypes();
        $seconds = $benchmark->stop()->elapsedSeconds();
        $this->info('Indices mapeados com sucesso!');
        $this->info('Levou '.$seconds.' segundos');
    }
}
