<?php

namespace App\Utils;

trait ImageFileServerTrait {

    protected $fileField = 'file';
    private $fileServerUrl;

    public function getImageUrl($params = []){
        return $this->buildImageUrl($params);
    }

    private function buildImageUrl($params = []){
        $fileField = $this->fileField;
        $defaultParams = ['path' => $this->$fileField];
        return route('image.get', array_merge($defaultParams,$params));
    }
    
}