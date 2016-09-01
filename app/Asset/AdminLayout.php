<?php

namespace App\Asset;

class AdminLayout extends AssetBundle
{
    public function __construct()
    {
        $basePath = url('/');
        $this->baseCssPath = $basePath.'/assets/css/admin/src/';
        $this->baseJsPath  = $basePath.'/assets/js/admin/src/';
        $this->jsfiles  = ["layout.js"];
        $this->cssfiles = ["layout.css"];
    }
}