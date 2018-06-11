<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Simple PHP age Calculator
 * 
 * Calculate and returns age based on the date provided by the user.
 * @param   date of birth('Format:yyyy-mm-dd').
 * @return  age based on date of birth
 */
function ageCalculator($dob)
{
	if(!empty($dob))
	{
		$birthdate = new DateTime($dob);
		$today   = new DateTime('today');
		$age = $birthdate->diff($today)->y;
		return $age;
	}
	else
	{
		return 0;
	}
}

function keyRename(array $hash, array $replacements) {
    // foreach($hash as $k=>$v)
    // {
        print_r(array_combine($hash, $replacements));

        /*
        if($ok=array_search($k,$replacements))
        {
          $hash[$ok]=$v;
          unset($hash[$k]);
        }*/
    // }

    return $hash;       
}

function escape($string) 
{
    return htmlentities($string, ENT_QUOTES, 'UTF-8');
}

function thousandsCurrencyFormat($num) {
    $x = round($num);
    $x_number_format = number_format($x);
    $x_array = strchr($x_number_format, ',') ? explode(',', $x_number_format) : $x_number_format;



    $x_parts = array('k', 'm', 'b', 't');
    $x_count_parts = count($x_array) - 1;

    $x_display = $x;
    if(is_array($x_array))
    {
        $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
    }
    if($x_count_parts > 0)
    {
        $x_display .= $x_parts[$x_count_parts - 1];
    }
    return $x_display;
}

function currentYear(){
    return Date('Y');
}

function custom_echo($arr, $col, $case_change = '') {
    return $case_change == 'no_case_change' ? (array_column($arr, $col)[0]) : ucwords(entity_decode(array_column($arr, $col)[0]));
}

function in_array_r($item , $array){
    return preg_match('/"'.$item.'"/i' , json_encode($array));
}

function get_partial_array_indices($array, $str, $start, $end){
    $indices = [];
    foreach($array as $key=>$value){
        if($str == substr($key, $start,$end)){
            $indices[] = substr($key,strrpos($key,'_'));
        }
    }

    foreach($indices as $key=>$value){
        $indices[$key]=str_replace("_","",$value);
    }

    return $indices;
}