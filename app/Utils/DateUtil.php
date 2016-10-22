<?php

namespace App\Utils;

class DateUtil{
    public static function dateTimeInBrazil($time = 'now'){
        return new \DateTime($time, new \DateTimeZone('America/Sao_Paulo'));
    }

    public static function strDbDateToBrDate($date,$withTime = true){
    	$arrDate = explode(" ",$date);
    	$formatedDate = implode('/',array_reverse(explode('-',$arrDate[0])));
    	if($withTime && isset($arrDate[1])){
    		$formatedDate .= " ".$arrDate[1];
    	}
    	return $formatedDate;
    }
 
    public static function strBrDateToDbDate($date,$withTime = true){
    	$arrDate = explode(" ",$date);
    	$formatedDate = implode('-',array_reverse(explode('/',$arrDate[0])));
    	if($withTime && isset($arrDate[1])){
    		$formatedDate .= " ".$arrDate[1];
    	}
    	return $formatedDate;	
    }

    public static function addDaysToDate(\DateTime $dateTime, $days){
        return $dateTime->modify("+".$days." days");
    }

    public static function addMonthsToDate(\DateTime $dateTime, $months){
        return $dateTime->modify("+".$months." months");   
    }

    public static function lastDayOfMonth($dateTime = null){
        if(empty($dateTime)){
            $dateTime = new \DateTime();
        }
        $dateTime->modify('last day of this month');
        return $dateTime->format('d');
    }

    public static function firstDayOfMonth(){
        if(empty($dateTime)){
            $dateTime = new \DateTime();
        }
        $dateTime->modify('first day of this month');
        return $dateTime->format('d');
    }

    public static function subDates(\DateTime $dateA, \DateTime $dateB, $format = null){
        $interval = $dateA->diff($dateB);
        if(!empty($format)){
            if($format == 'months'){
                $months = intval($interval->format('%m'));
                $years = intval($interval->format('%y'));
                $monthsInterval = ($years*12 + $months);
                $result = $monthsInterval;
            } else {
                $result =  $interval->format($format);
            }
            if($dateA->format('Y-m-d') >= $dateB->format('Y-m-d')){
                return $result;
            } else {
                return $result * -1; 
            }
        }
        return $interval;
    }

    public static function strBrDateToDateTime($strDate, $withTime = true){
       return  DateUtil::dateTimeInBrazil(DateUtil::strBrDateToDbDate($strDate, $withTime));
    }

    public static function betweenDates(\DateTime $start, \DateTime  $end, $needle = 'now')
    {
        if ($needle == 'now') {
            $needle =   DateUtil::dateTimeInBrazil();
        }
        $startTime = $start->getTimestamp();
        $neddleTime = $needle->getTimestamp();
        $endTime = $end->getTimestamp();
        if ($startTime <= $neddleTime && $neddleTime <= $endTime) {
            return true;
        }
        return false;
    }
}