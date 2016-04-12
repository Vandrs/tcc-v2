<?php

namespace App\Asset;

use Config;
use App\Utils\Utils;

class Bootstrap extends AssetBundle{
    protected $priority = 2;

    public function __construct() {
        $basePath = url('/');
        $this->baseCssPath = (env('APP_ENV','local') == 'local') ? $basePath.'/assets/css/bootstrap/src/' : $basePath.'/assets/css/bootstrap/dist/';
        $this->baseJsPath  = (env('APP_ENV','local') == 'local') ? $basePath.'/assets/js/bootstrap/src/'  : $basePath.'/assets/js/bootstrap/dist/';
        $this->jsfiles = (env('APP_ENV','local') == 'local') ? ['bootstrap.js'] : ['bootstrap.min.js'];
        $this->cssfiles = (env('APP_ENV','local') == 'local') ? ['bootstrap.css','bootstrap-theme.css'] : ['bootstrap.min.css','bootstrap-theme.min.css'];
    }
}