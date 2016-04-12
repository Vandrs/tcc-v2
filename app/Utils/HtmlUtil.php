<?php

namespace App\Utils;

class HtmlUtil{
    public static function options($items, $valueField, $labelField, $selected = null, $print = true){
        $html = "";
        if(empty($items)){
            $items = [];
        }
        foreach($items as $item){
            if(is_array($item)){
                $item = (object) $item;
            }
            if(is_array($selected)){
                $selected = (object) $selected;
            }

            $optionSelected = "";
                
            if(is_object($selected)){
                $optionSelected = (!empty($selected) && $selected->$valueField == $item->$valueField)?'selected':'';
            } else {
                $optionSelected = (!empty($selected) && $selected == $item->$valueField)?'selected':'';
            }
            $html .= "<option value='".$item->$valueField."' ".$optionSelected.">".$item->$labelField."</option>";
        }
        if($print){
            echo $html;
        } else {
            return $html;
        }
    }
    
    public static function propertyBedroomsAndGarage($labelField = "label", $valueField = "value"){
        return [
            [$labelField => trans('site.inputs.select'),$valueField => ""],
            [$labelField => "1 ".trans('site.or_more'),$valueField => 1],
            [$labelField => "2 ".trans('site.or_more'),$valueField => 2],
            [$labelField => "3 ".trans('site.or_more'),$valueField => 3],
            [$labelField => "4 ".trans('site.or_more'),$valueField => 4],
            [$labelField => "5 ".trans('site.or_more'),$valueField => 5]
        ];
    }
    
    public static function searchOrder(){
        return [
            ['label'=>trans('site.property.relevancy'),'value'=>''],
            ['label'=>trans('words.smallest_value'),'value'=>'value_asc'],
            ['label'=>trans('words.biggest_value'),'value'=>'value_desc']
        ];
    }
}