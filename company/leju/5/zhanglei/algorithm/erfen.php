<?php
function erfen($array, $value, $left, $right){
	$erfen_key = floor(($right - $left) / 2) + $left;

	if($left == $right && $array[$erfen_key] != $value){
		return 'none';
	}

	if($array[$erfen_key] == $value){
		return $erfen_key;
	}elseif($array[$erfen_key] > $value){
		return erfen($array, $value, $left, $erfen_key);
	}else{
		return erfen($array, $value, $erfen_key, $right);
	}
}

$array = array(1, 3, 5, 15, 85, 152, 365, 458, 569, 687, 754, 986);
$value = 152;
$right = count($array);
$left  = 0;
$key = erfen($array, $value, $left, $right);
echo $key;
?>