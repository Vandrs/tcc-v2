<?php

namespace App\Asset;

use Config;

class JqueryUI extends AssetBundle{
    protected $priority = 3;

    public function __construct() {
        $basePath = url('/');
        $this->baseCssPath = $basePath.'/assets/css/jqueryUI/dist/';
        $this->baseJsPath  = $basePath.'/assets/js/jqueryUI/dist/';
        $this->jsfiles  = ["jquery-ui.min.js"];
        $this->cssfiles = ["jquery-ui.min.css"];
    }
}