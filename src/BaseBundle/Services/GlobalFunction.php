<?php
namespace BaseBundle\Services;

class GlobalFunction
{
	public function __construct()
	{

	}
	
	public function orderArr($array, $sortby, $direction='asc') {
     
        $sortedArr = array();
        $tmp_Array = array();
         
        foreach($array as $k => $v) {
            $tmp_Array[] = strtolower($v->$sortby);
        }
         
        if($direction=='asc'){
            asort($tmp_Array);
        }else{
            arsort($tmp_Array);
        }
         
        foreach($tmp_Array as $k=>$tmp){
            $sortedArr[] = $array[$k];
        }
         
        return $sortedArr;

    }

}