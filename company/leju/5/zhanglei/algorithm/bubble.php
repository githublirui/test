<?php
function bubble($array){
	if(!is_array($array) || empty($array)){
		return $array;
	}
	
	for($i = 0; $i < count($array); $i++){
		for($j = $i + 1; $j < count($array); $j++){
			if($array[$i] >= $array[$j]){
				$tmp = $array[$i];
				$array[$i] = $array[$j];
				$array[$j] = $tmp;
			}
		}
	}
	return $array;
}

$array = array(3, 5, 6, 4, 45, 21, 52, 36, 56, 398, 896, 547, 123, 145, 52, 854, 459);
$sort_array = bubble($array);
print_r($sort_array);