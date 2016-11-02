<?php

namespace App\Utils;

class StringUtil{
    
    public static function toUrl($string){
        $string = self::removerAcentos($string);
        $string = self::tolower($string);
        return self::cleanExtraChars($string);
    }
    
    public static function cleanExtraChars($string){
        $search = ["[","]",">","<","}","{",",","*","~","^","'","\"","/","&","#","@","(",")","."," "];
        $search2 = ["º","!","?","%",":",";"];
        $search3 = ["–","---","--","--","-–-"];
        $string = str_replace($search,"-",$string);
        $string = str_replace($search2,"",$string);
        return str_replace($search3,"-",$string);
    }

    public static function cleanTabAndBreakLines($string){
        $string = trim(preg_replace('/\t+/', '', $string));
        $string = trim(preg_replace('/\n+/', '', $string));
        return $string;
    }
    
    public static function removerAcentos($string){
        
        $function = 'ereg_replace';
        
        if(function_exists('mb_ereg_replace')){
            $function = 'mb_ereg_replace';
        }

        $string = $function("[áàâãä]", "a", $string);
        $string = $function("[ÁÀÂÃÄ]", "A", $string);
        $string = $function("[éèê]", "e", $string);
        $string = $function("[ÉÈÊ]", "E", $string);
        $string = $function("[íì]", "i", $string);
        $string = $function("[ÍÌ]", "I", $string);
        $string = $function("[óòôõö]", "o", $string);
        $string = $function("[ÓÒÔÕÖ]", "O", $string);
        $string = $function("[úùü]", "u", $string);
        $string = $function("[ÚÙÜ]", "U", $string);
        $string = $function("ç", "c", $string);
        $string = $function("Ç", "C", $string);

        return $string;
    }
    
    public static function tolower($string){
        if(function_exists('mb_strtolower')){
            return mb_strtolower($string);
        }
        return strtolower($string);
    }
    
    public static function touper($string){
        if(function_exists('mb_strtoupper')){
            return mb_strtoupper($string);
        }
        return strtoupper($string);
    }
    
    public static function limitaCaracteres($string,$qtd,$append = ""){
        $string = trim($string);
        if(strlen($string) <= $qtd){
            return $string;
        }
        if(function_exists('mb_substr')){
            $string = mb_substr($string, 0,$qtd);
        } else {
            $string = substr($string, 0,$qtd);
        }
        $lastPosition = strrpos($string,' ');
        if(function_exists('mb_substr')){
            $string = mb_substr($string, 0,$lastPosition);
        } else {
            $string = substr($string, 0,$lastPosition);
        }
        return $string.$append;
    }
    
    public static function resolvePlural($count, $singular, $plural, $onNull = null, $onZero = null){
        if($count == 0  && !empty($onZero)){
            return $onZero;
        } else if($count === null && !empty($onNull)){
            return $onNull;
        } else if(empty($count) || $count == 1){
            return $singular;
        } else if($count > 1){
            return $plural;
        }
    }

    public static function mask($val, $mask) {
        $maskared = '';
        $k = 0;
        for($i = 0; $i <= strlen($mask)-1; $i++){
            if($mask[$i] == '#'){
                if(isset($val[$k])){
                    $maskared .= $val[$k++];
                }
            } else {
                if(isset($mask[$i])){
                    $maskared .= $mask[$i];
                }
            }
        }
        return $maskared;
    }

    public static function justNumbers($str){
        return preg_replace('/\D/', '', $str);
    }  
     
}