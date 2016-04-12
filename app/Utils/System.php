<?php

namespace App\Utils;
use App\Utils\StringUtil;

class System {

	private static $PHP_TUSAGE;
	private static $PHP_RUSAGE;

	public static function boot(){
		if(self::isUbuntu()){
			$dat = getrusage();
			self::$PHP_TUSAGE = microtime(true);
			self::$PHP_RUSAGE = $dat["ru_utime.tv_sec"]*1e6+$dat["ru_utime.tv_usec"];	
			return true;
		}
		return false;
	}

    public static function isUbuntu(){
        return str_contains(StringUtil::tolower(php_uname()),'ubuntu'); 
    }

    public static function isRunningFromCli(){
    	if (strpos(php_sapi_name(), 'cli') !== false) {
    		return true;
		}
		return false;
    }
    
    public static function ubuntuPercentualMemoryUsage(){
        $free = shell_exec('free');
		$free = (string)trim($free);
		$free_arr = explode("\n", $free);
		$mem = explode(" ", $free_arr[1]);
		$mem = array_filter($mem);
		$mem = array_merge($mem);
		$memory_usage = (($mem[2]-$mem[6])*100)/$mem[1];
		return round($memory_usage,2);
    }

    public static function ubuntuPercentualCPUUsage(){
    	$dat = getrusage();
	    $dat["ru_utime.tv_usec"] = ($dat["ru_utime.tv_sec"]*1e6 + $dat["ru_utime.tv_usec"]) - self::$PHP_RUSAGE;
	    $time = (microtime(true) - self::$PHP_TUSAGE) * 1000000;
	    if($time > 0) {
	        $cpu = sprintf("%01.2f", ($dat["ru_utime.tv_usec"] / $time) * 100);
	    } else {
	        $cpu = '0.00';
	    }
	    return (float) $cpu;
    }

}
