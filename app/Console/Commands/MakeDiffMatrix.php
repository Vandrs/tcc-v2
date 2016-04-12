<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Business\DiffMatrixBusiness;
use App\Utils\Benchmark;
use App\Utils\Utils;

class MakeDiffMatrix extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make-diff-matrix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calcula da matriz de diferenças';

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
        try{
            $benchmark = new Benchmark();
            $this->info('Iniciando cálculo da matriz de diferenças');
            $benchmark->start();
            $diffMatrixBusiness = new DiffMatrixBusiness($this->output);
            $diffMatrixBusiness->makeMatrix();
            $elapsetTime = $benchmark->stop()->elapsedSeconds() / 60;
            $this->info("Levou: ".$elapsetTime." minutos");
        } catch(\Exception $e){
            $msg = Utils::getExceptionFullMessage($e);
            $this->error($msg);
        }
    }
}
