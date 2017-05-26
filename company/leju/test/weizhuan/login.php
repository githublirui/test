<?php
session_start();
require('conn.php');
require('functions.php');
//全站访问
if($config['fangwen']==4){
	exit('网站正在更新');
}	
$ip=GetIP();
$day=date("Y-m-d",time());
$time=time();
if($_SESSION['login']==1){
	_location("ucenter.php",301);
	exit;
}

//登录验证
if($_POST){
	$phone=guolv(trim($_POST['phone']));
	$password=guolv(trim($_POST['password']));
	//$code=guolv(trim($_POST['code']));
	if(is_phone($phone)==false){
		echo "<script>alert('请输入正确的手机号');location.href='login.php'</script>";
		exit;
	}
	if($password==''){
		echo "<script>alert('请输入密码');location.href='login.php'</script>";
		exit;		
	}	
	// if($code!==$_SESSION['code']){
		// echo "<script>alert('验证码错误，请重新输入');location.href='login.php'</script>";
		// exit;			
	// }
	$row=$mysql->query("select * from `userdata` where `phone`='{$phone}' and `pass`='{$password}' limit 1");
	if($row){
		//登录赠送金额
		$row_login_ip=$mysql->query("select * from `refererdata` where `uid`='{$row[0]['id']}' and `aid`='0' and `ip`='{$ip}' and `day`='{$day}' limit 1");
		if(!$row_login_ip && is_mobile()==true){
			$mysql->query("insert into `refererdata` values(null,'{$row[0]['id']}','0','ID：{$row[0]['id']}用户登录','','{$config['daysong']}','{$ip}','{$day}','{$time}')");
			//加钱
			$mysql->query("update `userdata` set `money`=`money`+'{$config['daysong']}' where `id` in({$row[0]['id']}) limit 1");					
		}
		$_SESSION['userdata']=$row[0];
		$_SESSION['login']=1;
		_location("{$site}/ucenter.php",301);
		exit;
	}else{
		echo "<script>alert('用户名或密码错误');location.href='login.php'</script>";
		exit;
	}
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<title>登录 - <?php echo $config['sitename']?></title>
<meta name="keywords" content="<?php echo $config['sitename']?>,登录" />
<meta name="description" content="<?php echo $config['sitename']?>用户登录。">
<script type="text/javascript" src="<?php echo $site?>/static/jquery.js"></script>
<link href="<?php echo $site?>/static/all.css" type="text/css" rel="stylesheet" media="all">
<style>
body{margin:0;}
*{box-sizing:border-box;}
input{font-size: 16px;line-height: 1.25em;outline: 0px none;text-decoration: none;margin:0;}
</style>
</head>

<body>

<header style="min-height:44px;">
  <div class="logo"></div>
</header>

<div class="common-wrapper">
	<div style="padding:10px;text-align:center;margin:20px 20px 0;background:#fff;font-size:16px;border:1px dashed #f00;">已经发放<a style="color:#f00;font-weight:bold;"> 24787500 </a>元奖金</div>
	<div class="main">
		<form action="login.php" method="post">
			<div class="item">
				<input value="" class="txt-input txtpd" name="phone"  placeholder="请输入已验证手机号" type="text" />
			</div>
			<div class="item">
				<input value=""  name="password" class="txt-input txtpd" placeholder="请输入密码" type="password" />
			</div>
			<!--
			<div class="item">
				<input style="width: 40%;" name="code" type="text" class="txt-input txtpd" placeholder="请输入验证码" />
				<img src="code.php?<?php echo rand(1,99)?>">
			</div>		
			-->
			<div class="item item-btns"> 
			<input type="submit" value="登录" class="btn-login">
			</div>
			<div class="item item-btns"> 
			<a href="reg.php" class="btn-login">注册一个新帐号</a>
			</div>				
			<?php
			if($config['weixin_reg']==1){
			?>
			<div class="item item-btns"> 
			<a href="goto.php" class="btn-login" style="background: #00B266;">微信一键登录</a>
			</div>	
			<?php }?>
		</form>
		<div class="item item-login-option">
		<!--<span class="register-free"><a rel="nofollow" href="<?php echo $site?>/reg.php">免费注册</a></span>-->
		<!--<span class="retrieve-password"><a href="<?php echo $site?>/find.php">找回密码</a></span>-->
		</div>
	</div>
</div>


</body>
</html>