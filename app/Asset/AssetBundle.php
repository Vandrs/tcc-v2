<?php 

namespace App\Asset;

class AssetBundle{
    
    protected $priority = 10;
    protected $jsfiles = [];
    protected $cssfiles = [];
    protected $baseCssPath = '';
    protected $baseJsPath  = '';
    
    public static function instance(){
        $class = get_called_class();
        return new $class();
    }
    
    public function registerPackage(){
        return [
            'css'      => $this->makeCSSPaths(),
            'js'       => $this->makeJSPaths(),
            'priority' => $this->priority
        ];
    }
    
    private function makeCSSPaths(){
        if(!is_array($this->cssfiles)){
            $this->cssfiles = [$this->cssfiles];
        }
        $filePaths = [];
        foreach($this->cssfiles as $cssFile){
            if(is_array($cssFile) && $cssFile['external']){
                array_push($filePaths, $cssFile['path']);
            } else {
                array_push($filePaths, $this->baseCssPath.$cssFile);
            }
        }
        return $filePaths;
    }
    
    private function makeJSPaths(){
        if(!is_array($this->jsfiles)){
            $this->jsfiles = [$this->jsfiles];
        }
        $filePaths = [];
        foreach($this->jsfiles as $jsFile){
            if(is_array($jsFile) && $jsFile['external']){
                array_push($filePaths, $jsFile['path']);
            } else {
                array_push($filePaths, $this->baseJsPath.$jsFile);
            }
        }
        return $filePaths;
    }
}