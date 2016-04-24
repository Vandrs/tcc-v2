<?php

namespace App\Asset;
use Config;
use App\Utils\Utils;
class App extends AssetBundle{
    protected $priority = 3;
    public function __construct() {
        $basePath = url('/');
        $env = Config::get('app.env');
        $this->baseCssPath = ($env == 'local') ? $basePath.'/assets/css/app/src/' : $basePath.'/assets/css/app/dist/';
        $this->baseJsPath  = ($env == 'local') ? $basePath.'/assets/js/app/src/'  : $basePath.'/assets/js/app/dist/';
    	$this->cssfiles = ($env == 'local') ? ['app.css'] : ['app.min.css'];
    	$this->jsfiles  = ($env == 'local') ? ['app.js'] : ['app.min.js'];    
    }
}