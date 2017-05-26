<?php
if(file_exists(dirname(__FILE__) . '/inc.php')){
	include_once(dirname(__FILE__) . '/inc.php');
}else{
	throw new Exception('inc php is not exists');
}
set_time_limit(86400);
/*
    create table users(
        uid int unsigned not null auto_increment comment '自增长ID',
        username varchar(30) not null comment '用户名',
        password varchar(50) not null comment '用户密码',
        sex varchar(10) not null comment '用户性别',
        email varchar(30) not null comment '用户邮箱',
        created_time timestamp not null default current_timestamp comment '用户注册时间',
        primary key (uid),
        unique key (username),
        unique key (email)
    )engine=innodb default charset=utf8;
 */
$table = 'users';
$query_time_table = 'querytime';
$status = true;
while($status){
    $r_s_time = microtime('sec');
    $list = $redis->rpop($flag);
    $data = unserialize($list);
    // redis pop时间 以及 反序列化的时间差
    $r_e_time = microtime('sec') - $r_s_time;
    
	if(empty($data)){
		$status = false;
	}

    // 此处需要做unique的判断, 由于时间原因, 省略了。list.php需要时刻运行着, 以保证不断的从redis取出数据交给mysql, 减少mysql的并发, 有必要去写个shell的守护进程去看护
    $fields = 'uid';
	$email = isset($data['email']) ? $data['email'] : '';
    $e_s_time = microtime('sec');
	$email_record = $db->fetch($table, $fields, array('email' => $email));
    // 查询email所花费的时间
    $e_e_time = microtime('sec') - $e_s_time;
    
	$username = isset($data['username']) ? $data['username'] : '';
    $u_s_time = microtime('sec');
	$username_record = $db->fetch($table, $fields, array('username' => $username));
    // 查询username所花费的时间
	$u_e_time = microtime('sec') - $u_s_time;
    
	if(empty($email_record) && empty($username_record)){
        $i_s_time = microtime('sec');
		$result = $db->insert($table, $data);
        $i_e_time = microtime('sec') - $i_s_time;
	}
    
    // 插入querytime 目的查看时间耗费在哪里
    $array = array(
        'rtime' => $r_e_time,
        'etime' => $e_e_time,
        'utime' => $u_e_time,
        'itime' => $i_e_time
    );
    //$db->insert($query_time_table, $array);
}

/*
 * 短连接	mysqli_connect	41.243359088898
 * 长连接	new mysqli		39.714272022247
 * 去掉try	new mysqli		37.023116827011
 * 去掉插入 无				0.062004089355469
 */
echo json_encode(array('status' => 1));die;
