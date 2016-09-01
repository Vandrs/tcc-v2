<?php

namespace App\Asset;

use Config;

class Material extends AssetBundle{
    protected $priority = 3;
    public function __construct() {
        $basePath = url('/');
        $this->baseCssPath = $basePath.'/assets/css/material-bootstrap/dist/';
        $this->baseJsPath  = $basePath.'/assets/js/material-bootstrap/dist/';
        $this->jsfiles  = ['material.min.js','ripples.min.js'];
        $this->cssfiles = ['bootstrap-material-design.min.css','ripples.min.css'];
    }
}