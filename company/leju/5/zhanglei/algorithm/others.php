<?php

$n = 23;
for($i = 1; $i <= $n; $i++){
	$tmp = $i > ($n + 1)/2 ? $n - $i + 1 : $i;
	for($j = 1; $j <= $n; $j++){
		if(($j == ($n + 1)/2 + $tmp - 1) || ($j == ($n + 1)/2 - $tmp + 1)){
			echo "*";
		}else{
			echo "&nbsp;";
		}
	}
	echo "<br />";
}

$m = 8;
$yanghui = array();
for($i = 1; $i <= $m; $i++){
	$yanghui[$i][0] = 1;
	for($j = 1; $j < $i - 1; $j++){
		$yanghui[$i][$j] = $yanghui[$i - 1][$j - 1] + $yanghui[$i - 1][$j];
	}
	$yanghui[$i][$i - 1] = 1;
}
foreach($yanghui  as $key => $value){
	foreach($value as $num){
		echo $num . "&nbsp;&nbsp;";
	}
	echo '<br />';
}


function cattle($n = 10){
	static $num = 1;
	for($i = 1; $i <= $n; $i++){
		if($i >= 4 && $i < 15){
			$num++;
			cattle($n - $i);
		}
		if($i > 20){
			$num--;
		}
	}
	return $num;
}
echo cattle(11);
?>
