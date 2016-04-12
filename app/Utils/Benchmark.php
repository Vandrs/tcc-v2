<?php

namespace App\Utils;

class Benchmark{
    
    private $start;
    private $end;
    
    public function __construct(){
        $this->start = 0;
        $this->end = 0;
    }
    
    public function start(){
        list($usec, $sec) = explode(' ', microtime());
        $this->start = (float) $sec + (float) $usec;
        return $this;
    }
    
    public function stop(){
        list($usec, $sec) = explode(' ', microtime());
        $this->end = (float) $sec + (float) $usec;
        return $this;
    }
    
    public function elapsedSeconds(){
        return  round($this->end - $this->start, 3);
    }
    
}