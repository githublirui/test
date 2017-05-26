<?php

include("./db.class.php");
$db_config['hostname'] = "localhost";
$db_config['username'] = 'root';
$db_config['password'] = 'male365@qq.com';
$db_config['database'] = 'fuke';
$db_config['charset']  = "utf8";
$db = new mysqldb($db_config);

if($_POST['biaobao']< 10000){
	$jg = '对不起您暂时没有额度';
}elseif($_POST['biaobao']>= 10000 && $_POST['biaobao']< 30000){
	$jg = '限购一手';
}elseif($_POST['biaobao']>= 30000 && $_POST['biaobao']< 100000){
	$jg = '限购五手';
}elseif($_POST['biaobao']>= 100000 && $_POST['biaobao']< 500000){
	$jg = '限购二十五手';
}elseif($_POST['biaobao']> 500000){
	$jg = '限购五十手';
}else{
	$jg = '系统出错';
}

$sql =  "select * from members where name='".$_POST['name']."' and display=1 and tel = '".$_POST['tel']."' limit 1";
$res = mysql_query($sql);
$row = mysql_fetch_array($res, MYSQL_ASSOC);

$result = array();
if($row){
	if($row['biaobao']< 10000){
		$jg = '对不起您暂时没有额度';
	}elseif($row['biaobao']>= 10000 && $row['biaobao']< 30000){
		$jg = '限购一手';
	}elseif($row['biaobao']>= 30000 && $row['biaobao']< 100000){
		$jg = '限购五手';
	}elseif($row['biaobao']>= 100000 && $row['biaobao']< 500000){
		$jg = '限购二十五手';
	}elseif($row['biaobao']>= 500000){
		$jg = '限购五十手';
	}else{
		$jg = '系统出错';
	}
	$result['res'] = 'success';
	$result['biaobao'] = $jg;
	echo json_encode($result);
}else{
	$members_arr  = array(
            'name' => $_POST['name'],
            'gender'=> $_POST['gender'],
            'birthyear'=>$_POST['birthyear'],
            'birthmon'=>$_POST['birthmon'],
            'birthday'=>$_POST['birthday'],
            'tel'=>$_POST['tel'],
            'biaobao'=>$_POST['biaobao'],
            'display'=>'2',
            'dateline'=>time()
        );
	$db->row_insert('members', $members_arr);
	$uid = mysql_insert_id();
	if($uid){
		$result['res'] = 'success';
		$result['biaobao'] = $jg;
		echo json_encode($result);
	}else{
		$result['res'] = 'system_error';
		$result['biaobao'] = $jg;
		echo json_encode($result);
	}
}
	
?>