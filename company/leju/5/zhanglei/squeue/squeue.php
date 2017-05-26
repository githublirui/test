<?php

if(file_exists('./squeue.class.php')) require_once('./squeue.class.php');
if(file_exists('../mysql/DbHelper.class.php')) require_once('../mysql/DbHelper.class.php');

$conf = array(
    'host'  => 'localhost',
    'user'  => 'root',
    'pass'  => 'root',
    'db'    => 'demo',
    'character' => 'utf8'
);

$db_helper = DbHelper::getInstance($conf);
$memcache = Memcache_Queue::getInstance();

$key = 'chart';
$squeue_list = $memcache->getKeyValue($key);
if(!is_array($squeue_list)){
    return false;
}

/******** 取得memcache插入数据库 ********/
foreach($squeue_list as $key => $list){
    if(!empty($list)){
        $list['ip'] = ip2long($list['ip']);
        $result = $db_helper->write('chart', $list, '', 0);
        if($result) 
            $memcache->delete($key);
        else
            $memcache->set('mysqlerror', serialize(array('mysql_error' => mysql_error(), 'mysql_errno' => mysql_errno())));
        if(!$result) continue;
    }
}
/*****由于时间关系, mysql中不应该存取memcache中其他的值, 只存储memcache聊天记录*****/
?>