<?php

namespace App\Asset;
use Config;
use App\Utils\Utils;
class Jquery extends AssetBundle{
    protected $priority = 1;    
    public function __construct() {
        $basePath = url('/');
        $env = Config::get('app.env');
        $this->baseJsPath = ($env == 'local') ? $basePath.'/assets/js/jquery/src/' : $basePath.'/assets/js/jquery/dist/';
        $this->jsfiles = ($env == 'local') ? ['jquery-1-12-3.js'] : ['jquery-1-12-3.min.js'];
    }
}