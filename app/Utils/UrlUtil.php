<?php

namespace App\Utils;
use App\Utils\StringUtil;

class UrlUtil {
	
	public static function getIdByUrlPath($path){
		$position = strrpos($path,'-');
        $id = substr($path,++$position);
        return $id;
	}

	public static function makeExternal($url){
		if(str_contains($url,"http://") || str_contains($url,"https://")){
			return $url;
		}
		return "http://".$url;
	}
	
}