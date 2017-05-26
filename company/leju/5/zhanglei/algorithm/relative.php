<?php
/**
 * @brief 查找两个文件夹的相对路径, 查找出b相对于a的相对路径
 */
function relative($folder_a, $folder_b){
	$folder_a_arr = explode('/', $folder_a);
	$folder_b_arr = explode('/', $folder_b);
	
	for($i = 0; $i < count($folder_a_arr); $i++){
		if($folder_a_arr[$i] != $folder_b_arr[$i]){
			break;
		}
	}
	$relative = '';
	for($j = $i; $j < count($folder_b_arr); $j++){
		$tmp[] = $folder_b_arr[$j];
	}
	$relative = implode('/', $tmp);
	$temp = '';
	for($x = $i; $x < count($folder_a_arr); $x++){
		$temp .= '../';
	}
	return $temp . $relative;
}

$folder_a = '/a/b/c/d/e';
$folder_b = '/a/b/f/v/e/b';

$relative = relative($folder_a, $folder_b);
echo $relative;