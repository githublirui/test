<?php

$content = file_get_contents($GLOBALS['now_path'] . '/vrpano_map.php');
$length = strlen($content);

for ($i = 0; $i < $length; $i++) {
    var_dump(ord($content[$i]));
    die;
}
die;
$string = "z";
$length = strlen($string);
//var_dump($string); //ԭʼ���� 
//var_dump($length); //���� 
$result = array();

for ($i = 0; $i < $length; $i++) {
    var_dump(ord($string[$i]));
    if (ord($string[$i]) > 127) {
//        $result[] = $string[$i] . ' ' . $string[++$i];
    }
}
die;
var_dump($result);
?>