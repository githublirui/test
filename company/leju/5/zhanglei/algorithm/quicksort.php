<?php
function quicksort($array){
	if(!is_array($array) || empty($array)){
		return $array;
	}
	if(count($array) == 1){
		return $array;
	}
	
	$refer = $array[0];

	$left = $middle = $right = array();
	
	foreach($array as $value){
		if($refer > $value){
			$left[] = $value;
		}elseif($refer == $value){
			$middle[] = $value;
		}else{
			$right[] = $value;
		}
	}
	$left = quicksort($left);
	$right = quicksort($right);
	return array_merge($left, $middle, $right);
}

$array = array(8, 85, 56, 24, 365, 547, 985, 28, 67, 53);
$sort = quicksort($array);
print_r($sort);