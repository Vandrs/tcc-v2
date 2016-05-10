<?php

namespace App\Asset;
use Config;
use App\Utils\Utils;
class FileUpload extends AssetBundle{
    protected $priority = 3;    
    public function __construct() {
        $basePath = url('/');
        $env = Config::get('app.env');
        $this->baseJsPath = ($env == 'local') ? $basePath.'/assets/js/fileUpload/src/' : $basePath.'/assets/js/fileUpload/dist/';
        $this->jsfiles = ($env == 'local') ? ['jquery-ui-widget.js','jquery-fileupload.js','jquery-iframe-transport.js'] : ['source.js'];
    }
}