<?php 

/* @var self::$instance  app\Asset\AssetLoader */

namespace App\Asset;

class AssetLoader{
    
    private static $instance;
    private $bundles = [];
    private $scripts = [];
    private $styles = [];
    private $defaultBundles = ['Bootstrap','Jquery','Material','App'];
    private $assetsBundles = [];
    private $excludeBundles = [];
    private $loaded = FALSE;
    private $bundleNamespace = "App\Asset";
    private $appJsFilesSrc = "";
    private $appJsFilesMin = "";
    private $appCssFilesSrc = "";
    private $appCssFilesMin = "";
    private $cssToLoad = [];
    private $jsToLoad = [];
    
    public function __construct() {
        $basePath = url('/');
        $this->appJsFilesSrc  = $basePath."/assets/js/app/src/";
        $this->appJsFilesMin  = $basePath."/assets/js/app/dist/";
        $this->appCssFilesSrc = $basePath."/assets/css/app/src/";
        $this->appCssFilesMin = $basePath."/assets/css/app/dist/";
    }
    
    public static function register($scripts = [], $styles = [], $assetsBundles = [],  $excludeBundles = []){
        if(empty(self::$instance)){
            self::$instance = new AssetLoader();
        }
        self::$instance->setScripts($scripts);
        self::$instance->setStyles($styles);
        self::$instance->setAssetsBundles($assetsBundles);
        self::$instance->setExcludeBundles($excludeBundles);
    }
    
    public static function js(){
        if(empty(self::$instance)){
            self::$instance = new AssetLoader();
        }
        return self::$instance->getJsToLoad();
    }
    
    public static function css(){
        if(empty(self::$instance)){
            self::$instance = new AssetLoader();
        }
        return self::$instance->getCssToLoad();
    }
    
    
    private function defineBundles(){
        $this->bundles = array_merge($this->defaultBundles,$this->assetsBundles);
        foreach($this->bundles as $index => $bundle){
            if(in_array($bundle, $this->excludeBundles)){
                unset($this->bundles[$index]);
            }
        }
    }

    private function resolvePriority(& $registeredBundles){
        $sortBundles = function($bundle1,$bundle2){
            if($bundle1['priority'] == $bundle2['priority']){
                return 0;
            } else if($bundle1['priority'] > $bundle2['priority']){
                return 1;
            } else {
                return -1;
            }
        };
        usort($registeredBundles, $sortBundles);
    }
    
    private function loadBundles(){
        $this->defineBundles();
        $registerBundles = [];
        foreach($this->bundles as $bundle){
            $class = $this->bundleNamespace."\\".$bundle;
            array_push($registerBundles,$class::instance()->registerPackage());
        }
        $this->resolvePriority($registerBundles);
        $this->registerPackages($registerBundles);        
        $this->loaded = TRUE;
    }
    
    private function registerPackages($registerBundles){
        $js = [];
        $css = [];
        
        foreach($registerBundles as $bundle){
            $css = array_merge($css,$bundle['css']);
            $js = array_merge($js,$bundle['js']);
        }
                
        foreach($this->styles as $style){
            if(is_array($style) && isset($style['external']) && $style['external']){
                array_push($css, $style['path']);
            } else {
                array_push($css, $this->resolveCssMinFiles($style));
            }
        }
        
        foreach($this->scripts as $script){
            if(is_array($script) && isset($script['external']) && $script['external']){
                array_push($js, $script['path']);
            } else {
                array_push($js, $this->resolveJsMinFiles($script));
            }
        }
        
        $this->cssToLoad = array_unique($css);
        $this->jsToLoad = array_unique($js);
    }
    
    private function resolveJsMinFiles($filePath){
        if(env("APP_ENV","local") == "local"){
            return $this->appJsFilesSrc.$filePath;
        } else {
            $length = strlen($filePath) -3;
            $fileName = substr($filePath,0,$length);
            return $this->appJsFilesMin.$fileName.".min.js";
        }
    }
    
    private function resolveCssMinFiles($filePath){
        if(env("APP_ENV","local") == "local"){
            return $this->appCssFilesSrc.$filePath;
        } else {
            $length = strlen($filePath) -4;
            $fileName = substr($filePath,0,$length);
            return $this->appCssFilesMin.$fileName.".min.css";
        }
    }

    function getJsToLoad(){
        if(!$this->loaded){
            $this->loadBundles();
        }
        return $this->jsToLoad;
    }
    
    function getCssToLoad(){
        if(!$this->loaded){
            $this->loadBundles();
        }
        return $this->cssToLoad;
    }
    
    function setBundles($bundles) {
        $this->bundles = $bundles;
    }

    function setScripts($scripts) {
        $this->scripts = $scripts;
    }

    function setStyles($styles) {
        $this->styles = $styles;
    }

    function setAssetsBundles($assetsBundles) {
        $this->assetsBundles = $assetsBundles;
    }

    function addAssetBundle($assetBundle){
        if(empty(self::$instance)){
            self::$instance = $this;
            self::register();
        }
        array_push(self::$instance->assetsBundles,$assetBundle);   
    }

    function setExcludeBundles($excludeBundles) {
        $this->excludeBundles = $excludeBundles;
    }    
}