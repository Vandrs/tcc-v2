<?php

namespace App\Asset;

use Config;

class LightGallery extends AssetBundle
{
    public function __construct()
    {
        $basePath = url('/');
        $this->baseCssPath = $basePath.'/assets/css/lightGallery/dist/';
        $this->baseJsPath  = $basePath.'/assets/js/lightGallery/dist/';
        $this->jsfiles  = ["lightgallery-all.min.js"];
        $this->cssfiles = ["lg-fb-comment-box.min.css","lg-transitions.min.css","lightgallery.min.css"];
    }

}