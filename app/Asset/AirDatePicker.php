<?php 

namespace App\Asset;

class AirDatePicker extends AssetBundle{
	protected $priority = 3;

	public function __construct(){
		$basePath = url("/");
		$this->baseCssPath = $basePath.'/assets/css/airDatePicker/dist/';
        $this->baseJsPath  = $basePath.'/assets/js/airDatePicker/dist/';
        $this->jsfiles  = ['datepicker.min.js','datepicker.pt-BR.js'];
        $this->cssfiles = ['datepicker.min.css'];
	}
}