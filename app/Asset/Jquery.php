<?php

namespace App\Asset;
use Config;
use App\Utils\Utils;
class Jquery extends AssetBundle{
    protected $priority = 1;    
    public function __construct() {
        $basePath = url('/');
        $this->baseJsPath = (env('APP_ENV','local') == 'local') ? $basePath.'/assets/js/jquery/src/' : $basePath.'/assets/js/jquery/dist/';
        $this->jsfiles = (env('APP_ENV','local') == 'local') ? ['jquery-1-12-3.js'] : ['jquery-1-12-3.min.js'];
    }
}