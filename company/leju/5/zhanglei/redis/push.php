<?php
if(file_exists(dirname(__FILE__) . '/inc.php')){
	include_once(dirname(__FILE__) . '/inc.php');
}else{
	throw new Exception('inc.php is not exists');
}

$num = 500000;
for($i = 0; $i < $num; $i++){  
    $data['sex'] = array_rand(array("man" => 1, "woman" => 2));
    $strings = array_flip(range("a", "z"));
    $prefix = implode(array_rand($strings, 8), "") . '_' . $i;
    $data['email'] = $prefix . "@sina.com.cn";
    $data['username'] = $prefix;
    $data['password'] = md5($data['username']);
    $redis->lpush($flag, serialize($data));
}

echo json_encode(array('status' => 1));die;
?>