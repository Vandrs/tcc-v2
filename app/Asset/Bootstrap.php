<?php

namespace App\Asset;

use Config;
use App\Utils\Utils;

class Bootstrap extends AssetBundle{
    protected $priority = 2;

    public function __construct() {
        $basePath = url('/');
        $env = Config::get('app.env');
        $this->baseCssPath = ($env == 'local') ? $basePath.'/assets/css/bootstrap/src/' : $basePath.'/assets/css/bootstrap/dist/';
        $this->baseJsPath  = ($env == 'local') ? $basePath.'/assets/js/bootstrap/src/'  : $basePath.'/assets/js/bootstrap/dist/';
        $this->jsfiles  = ($env == 'local') ? ['bootstrap.js'] : ['bootstrap.min.js'];
        $this->cssfiles = ($env == 'local') ? ['bootstrap.css'] : ['bootstrap.min.css'];
    }
}