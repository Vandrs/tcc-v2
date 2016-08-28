<?php 

namespace App\Asset;

class DataTables extends AssetBundle {
	
	protected $priority = 3;

	public function __construct(){
		$basePath = url('/');
		$this->baseCssPath = $basePath.'/assets/css/dataTables/src/';
        $this->baseJsPath  = $basePath.'/assets/js/dataTables/src/';
        $this->jsfiles  = ['datatables.min.js'];
        $this->cssfiles = ['dataTables.bootstrap.min.css'];
	}
	
}