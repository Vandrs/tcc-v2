<?php

namespace App\Utils;

class Utils{
    public static function isProduction(){
        return env('APP_ENV') == 'production';
    }

    public static function addSALT(){
    	return env('PASSWORD_SALT');
    }

    public static function radomName(){
        $data = new \DateTime('now');
        $name = self::generatePassword('alpha',6);
        return $name.$data->format('ymdHis');
    }
    
    public static function generateUTM($source, $medium, $campaign){
        return "?utm_source=".$source."&utm_medium=".$medium."&utm_campaign=".$campaign;        
    }

    public static function generatePassword($type = 'alnum', $len = 8){
        switch($type) {
            case 'basic'   : return mt_rand();
            break;
            case 'alnum'   :
            case 'numeric' :
            case 'nozero'  :
            case 'alpha'   :
            switch ($type) {
               case 'alpha'   :  $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
               break;
               case 'alnum'   :  $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
               break;
               case 'numeric' :  $pool = '0123456789';
               break;
               case 'nozero'  :  $pool = '123456789';
               break;
            }
            $str = '';
            for ($i=0; $i < $len; $i++) {
               $str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
            }
            return $str;
            break;
            case 'unique'  :
            case 'md5'     :
            return md5(uniqid(mt_rand()));
            break;
        }
    }

    public static function coalesce(){
        $args = func_get_args();
        foreach($args as $arg){
            if(!empty($arg)){
                return $arg;
            }
        }
        return null;
    }

    public static function getExceptionFullMessage(\Exception $e){
      $message = $e->getMessage();
      $message .= ". File: ".$e->getFile();
      $message .= ". Line: ".$e->getLine();
      return $message;
    }

    public static function calcFromIndex($page = 0, $size){
      if($page <= 1){
        return 0;
      }
      return (($page - 1) * $size);
    }

}
