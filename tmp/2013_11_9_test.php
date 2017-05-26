<?php



$s = urlencode('中国家网');

$data = json_encode($s);


var_dump(urldecode($data));
