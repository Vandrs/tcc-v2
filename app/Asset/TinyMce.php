<?php

namespace App\Asset;

class TinyMce extends AssetBundle {
	
	protected $priority = 3;

	public function __construct(){
		$basePath = url('/');
        $this->baseJsPath  = $basePath.'/assets/js/tinymce/';
        $this->jsfiles  = ['tinymce.min.js'];
	}
	
}