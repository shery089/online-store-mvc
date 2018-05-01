<?php

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

    foreach($hash as $k=>$v)
        if($ok=array_search($k,$replacements))
        {
          $hash[$ok]=$v;
          unset($hash[$k]);
        }

    return $hash;       
}