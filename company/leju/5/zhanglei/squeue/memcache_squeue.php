<?php

if(file_exists('./squeue.class.php')) require_once('./squeue.class.php');
$memcache = Memcache_Queue::getInstance();

$fix = 'chart';
$limit = 7;

$return = array();
$content = $_POST['content'];
$refresh = $_POST['refresh'];

if(!empty($_SERVER["HTTP_CLIENT_IP"])){
    $ip = $_SERVER["HTTP_CLIENT_IP"];
}elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
    $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
}elseif(!empty($_SERVER["REMOTE_ADDR"])){
    $ip = $_SERVER["REMOTE_ADDR"];
}else{
    $ip = "";
}

// 用户刷新了, 去除此刷新的用户显示的聊天记录
if($refresh == 'true'){
    $memcache->set('refresh_count', $memcache->getCount($fix));
}
$refresh_count = $memcache->get('refresh_count') ? $memcache->get('refresh_count') : 0;

if(!$content) $return = array('status' => 0, 'message' => 'please input your words');

$string = serialize(array('ip' => $ip, 'content' => $content));

if(!$memcache->input($fix, $string) && strtolower($_SERVER['EQUEST_METHOD']) == 'post'){
    $return = array('status' => 1, 'message' => 'memcache set is error');
}else{
    $count = $memcache->getCount($fix);
    $diff = $count - $refresh_count;
    $start = $diff - $limit > 0 ? $count - $limit + 1 : $refresh_count + 1;
    $data = $memcache->getDataByPage($fix, $start, $count);
    $return = array('status' => 2, 'message' => 'suc', 'data' => $data);
}
echo json_encode($return);die;
?>