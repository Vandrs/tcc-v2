<?php

namespace App\Asset;
use Config;
use App\Utils\Utils;
class FileUpload extends AssetBundle{
    protected $priority = 3;    
    public function __construct() {
        $basePath = url('/');
        $this->baseJsPath = $basePath.'/assets/js/fileUpload/src/';
        $this->jsfiles = ['jquery-ui-widget.js','jquery-fileupload.js','jquery-iframe-transport.js'];
    }
}