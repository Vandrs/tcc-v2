<?php

namespace App\Asset;

class ChartJS extends AssetBundle {
	
	protected $priority = 3;

	public function __construct(){
		$basePath = url('/');
        $this->baseJsPath  = $basePath.'/assets/js/chartJS/dist/';
        $this->jsfiles  = ['Chart.min.js'];
	}
	
}