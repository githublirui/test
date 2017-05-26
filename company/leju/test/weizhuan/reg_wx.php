<?php
session_start();
require('conn.php');
require('functions.php');
if($_SESSION['login']==1){
	_location("ucenter.php",301);
	exit;
}
$ip=GetIP();
// if($config['openreg']!==2){
	// _location("reg.php",301);
	// exit;
// }
if($config['openreg']==0){
		echo "<script>alert('注册关闭，请联系我们');location.href='reg.php'</script>";
		exit;		
}
//获取wgateid
$wgateid=guolv($_GET['wgateid']);
$verify=guolv($_GET['verify']);
if($wgateid!=='' && $verify!==''){
	//验证
	$res=get_contents("http://www.weixingate.com/verify.php?wgateid={$wgateid}&verify={$verify}");
	if($res=='false'){
		//验证失败返回手机注册
		_location("reg.php",301);
		exit;		
	}
}

//注册
	$tj_id=0;//推荐人
	
	$row=$mysql->query("select * from `userdata` where `wgateid`='{$wgateid}' limit 1");
	if(!$row){
		$arr=array(
			//'id'=>null,
			'tj_id'=>$tj_id,
			'phone'=>'',
			'pass'=>'123456',
			'money'=>$song,
			'wx'=>'',
			'realname'=>'',
			'alipay'=>'',
			'wgateid'=>$wgateid,
			'ip'=>$ip,
			'kou'=>100,
			'day'=>date("Y-m-d",time()),
			'time'=>time(),
		);
		$value=arr2s($arr);
		$mysql->query("insert into `userdata` {$value}");
		$id=mysql_insert_id();
		if($id!==0){
			$_SESSION['userdata']=$row[0];
			$_SESSION['userdata']['id']=$id;
			$_SESSION['login']=1;
			_location("{$site}/ucenter.php",301);
			exit;
		}
	}else{	
		//已经注册
		$_SESSION['userdata']=$row[0];
		$_SESSION['login']=1;
		_location("{$site}/ucenter.php",301);
		exit;		
	}

?>