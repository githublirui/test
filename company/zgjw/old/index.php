<?php

function FilterStrForMysql($str_arr) {
    if (is_array($str_arr)) {
        foreach ($str_arr as $key => $value) {
            if (is_array($value)) {
                $str_arr[$key] = mysql_real_escape_string($value);
            } else {
                $str_arr[$key] = mysql_real_escape_string($value);
            }
        }
    } else {
        $str_arr = mysql_real_escape_string($str_arr);
    }
    return $str_arr;
}


for($i=1;$i<=9;$i++){
  $s= 123456789;
  $n = str_replace($i,"",$s);
 
  $num = $n * 9*$i; var_dump($num);

  if($num==$i.$i.$i.$i.$i.$i.$i.$i.$i) {
	break;
  }
}
var_dump($i);die;