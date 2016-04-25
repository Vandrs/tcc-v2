<?php

namespace App\Utils;

trait ProgressBarTrait {

	protected $output;
	private $progressBar;

	/* Classes para controle de progresso */
	public function setOutPut($outPut = null){
		$this->outPut = $outPut;
	}

	public function createProgressBar($qtdItems){
		if(!empty($this->outPut)){
			$this->progressBar = $this->outPut->createProgressBar($qtdItems);
		}
	}

	public function advanceBar(){
		if(!empty($this->progressBar)){
			$this->progressBar->advance();
		}
	}

	public function finishBar(){
		if(!empty($this->progressBar)){
			$this->progressBar->finish();
		}
	}
	
}