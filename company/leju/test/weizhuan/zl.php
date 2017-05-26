<?php
require('conn.php');
require('session.php');
require('functions.php');
//print_r($session);

//修改资料
if($_POST){
	//$wx=guolv(trim($_POST['wx']));
	//$pass=guolv(trim($_POST['pass']));
	$uid=guolv(trim($_POST['uid']));
	$realname=guolv(trim($_POST['realname']));
	$alipay=guolv(trim($_POST['alipay']));
	$phone=guolv(trim($_POST['phone']));
	$wx=guolv(trim($_POST['wx']));

	if($userdata['realname']!=='' && $userdata['alipay']!=='' && $userdata['phone']!==''){
		echo "<script>alert('收款姓名、支付宝、手机号，一经填写不得修改！');location.href='zl.php'</script>";
		exit;			
	}
	
	if($alipay=='' || $realname=='' || $phone==''){
		echo "<script>alert('请填写每一项内容，一经填写不得修改！');location.href='zl.php'</script>";
		exit;		
	}
	
	if(is_phone($phone)==false){
		echo "<script>alert('请输入正确的手机号');location.href='zl.php'</script>";
		exit;
	}

	$row=$mysql->query("select * from `userdata` where `alipay`='{$alipay}' limit 1");
	if($row){
		echo "<script>alert('支付宝已经被注册');location.href='zl.php'</script>";
		exit;
	}
	$mysql->execute("update `userdata` set `phone`='{$phone}',`alipay`='{$alipay}',`realname`='{$realname}',`wx`='{$wx}' where `id`='{$uid}'");
	echo "<script>alert('修改成功');location.href='zl.php'</script>";
	exit;
	
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<title>用户资料 - <?php echo $config['sitename']?></title>
<meta name="keywords" content="<?php echo $config['sitename']?>,用户资料" />
<meta name="description" content="<?php echo $config['sitename']?>用户资料">
<link href="<?php echo $site?>/static/all.css" type="text/css" rel="stylesheet" media="all">
<style>
td {
	border-bottom: 1px solid #ddd;
	line-height: 24px;
	font-size: 14px;
	color: #333;
	padding:5px 0;
}
.mhome{background:#fff;}
</style>
</head>

<body class="mhome">
<?php include('header.php')?>
<div style="width:96%;margin:0 auto;">
<div class="common-wrapper">
<p style="font-size:18px;font-weight:bold;color:#dc2635;text-align:center;padding-top:10px;"><a href="zl.php">用户资料</a>	&nbsp;&nbsp;&nbsp;&nbsp;
<a href="editpwd.php">密码修改</a></p>

	<div class="main">
		<form action="zl.php" method="post">
		<input type="hidden" value="<?php echo $session['id']?>" name="uid">
			<div class="item">
				<input value="<?php echo $userdata['phone']?>" class="txt-input txtpd" name="phone"  placeholder="手机号" type="text" />
			</div>
			<div class="item">
				<input value="<?php echo $userdata['realname']?>"  name="realname" class="txt-input txtpd" placeholder="收款姓名" type="text" />
			</div>
			<div class="item">
				<input value="<?php echo $userdata['alipay']?>"  name="alipay" class="txt-input txtpd" placeholder="支付宝" type="text" />
			</div>				
			<div class="item">
				<input value="<?php echo $userdata['wx']?>"  name="wx" class="txt-input txtpd" placeholder="微信号" type="text" />
			</div>			
			<!--
			<div class="item">
				<input style="width: 40%;" name="code" type="text" class="txt-input txtpd" placeholder="请输入验证码" />
				<img src="code.php?<?php echo rand(1,99)?>">
			</div>		
			-->
		<div style="font-size:14px;line-height:24px;border:1px dashed #f00;background:#fff;padding:5px;margin-bottom:20px;width:100%;">
		
		请正确填写<u>支付宝</u>、<u>收款姓名</u>、<u>手机号</u>，直接用于支付宝提现，一经填写不得修改！
		</div>			
			<div class="item item-btns"> 
			<input type="submit" value="保存" class="btn-login">
			</div>
		</form>

		<div class="item item-login-option">
		
		<!--<span class="retrieve-password"><a href="<?php echo $site?>/find.php">找回密码</a></span>-->
		</div>
	</div>
</div>
</div>
<br><br><br>
<?php
include('footer.php');
?>

</body>
</html>
