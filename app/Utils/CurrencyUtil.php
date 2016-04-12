<?php
namespace App\Utils;

class CurrencyUtil{
    
    public static function brToFloat($strtofloat){
        if(is_null($strtofloat) or $strtofloat == '' ) $strtofloat = 0;
        return (float)preg_replace(array('/\./','/,/'),array('','.'),$strtofloat);
    }
    
    public static function floatToBr($floattostr){
        return number_format($floattostr, 2, ',', '.');
    }
    
    public static function floatToBrUser($floattostr){
        return number_format($floattostr, 0, ',', '.');
    }

    public static function trunkDecimals($floattostr){
        return number_format($floattostr, 0, ',', '.');   
    }

    public static function valueToCieloValue($value){
        if(!is_numeric($value)){
            $value = self::brToFloat($value);
        }
        $strValue = (string)$value;
        $decimalDigits = strlen(substr(strrchr($strValue, "."), 1));
        if($decimalDigits < 2){
            $length = strlen($strValue) + 2-$decimalDigits;
            $strValue = str_pad($strValue, $length, "0");
        }
        return (int)str_replace(['.',','],"",$strValue);
    }

    
}

