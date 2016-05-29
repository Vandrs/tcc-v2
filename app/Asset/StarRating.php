<?php

namespace App\Asset;

class StarRating extends AssetBundle
{
    public function __construct()
    {
        $basePath = url('/');
        $this->baseCssPath = $basePath.'/assets/css/starRating/dist/';
        $this->baseJsPath  = $basePath.'/assets/js/starRating/dist/';
        $this->jsfiles  = ["star-rating.min.js","star-rating_locale_pt-br.js"];
        $this->cssfiles = ["star-rating.min.css"];
    }
}