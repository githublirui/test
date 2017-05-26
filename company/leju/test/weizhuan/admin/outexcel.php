<?php
session_start();
require('../conn.php');
require('../functions.php');
require('admin.php');

$filename="tx.xls";//先定义一个excel文件
header("Content-Type: application/vnd.ms-execl"); 
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=$filename"); 
header("Pragma: no-cache"); 
header("Expires: 0");

$day=date("Y-m-d",time());
$rows=$mysql->query("select * from `txdata` where `state`=0 order by `id` desc");
foreach($rows as $row){
	$realname=iconv('utf-8','gb2312',$row['realname']);
	if($row['sx1']!==''){//提成处理
		$sx1_money=$row['money']*($config['tc']/100);//1级上线提成
		$mysql->execute("update `userdata` set `money`=`money`+{$sx1_money} where `id`='{$row['sx1']}'");//结算上线		
		//写入状态
		$arr_tx=array(
			//'id'=>null,
			'uid'=>$row['sx1'],
			'aid'=>'',
			'title'=>"下线会员ID：{$row['sx1']}提现分成奖励",
			'long'=>'#',
			'money'=>$sx1_money,
			'ip'=>'',
			'day'=>$day,
			'time'=>time(),
		);
		$value=arr2s($arr_tx);
		$mysql->query("insert into `refererdata` {$value}");
	}
	echo $realname."\t";
	echo $row['alipay']."\t";
	echo $row['money']."\t";
}
$mysql->execute("update `txdata` set `state`=1 where `state`=0");//修改所有申请状态
?>