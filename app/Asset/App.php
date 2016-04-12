<?php

namespace App\Asset;
use Config;
use App\Utils\Utils;
class App extends AssetBundle{
    protected $priority = 3;
    public function __construct() {
        $basePath = url('/');
        $this->baseCssPath = env('APP_ENV','local') == 'local' ? $basePath.'/assets/css/app/src/' : $basePath.'/assets/css/app/dist/';
        $this->baseJsPath  = env('APP_ENV','local') == 'local' ? $basePath.'/assets/js/app/src/'  : $basePath.'/assets/js/app/dist/';
    	$this->cssfiles = env('APP_ENV','local') == 'local' ? ['app.css'] : ['app.min.css'];
    	$this->jsfiles  = env('APP_ENV','local') == 'local' ? ['app.js'] : ['app.min.js'];    
    }
}