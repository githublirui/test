<?php
require('conn.php');
require('session.php');
require('functions.php');
//print_r($session);

//修改资料
if($_POST){
	$pass_old=guolv(trim($_POST['pass_old']));
	$pass_new=guolv(trim($_POST['pass_new']));
	$pass_new1=guolv(trim($_POST['pass_new1']));
	$uid=guolv(trim($_POST['uid']));

	if($pass_old!=='' && $pass_new!=='' && $pass_new1!==''){	
		if($pass_old!==$session['pass']){
			echo "<script>alert('用户原密码不正确');location.href='editpwd.php'</script>";
			exit;
		}
		
		

		if($pass_new!==$pass_new1){
			echo "<script>alert('两次输入的密码不正确');location.href='editpwd.php'</script>";
			exit;
		}
		
		$mysql->execute("update `userdata` set `pass`='{$pass_new}' where `id`='{$uid}'");
		echo "<script>alert('修改成功请重新登录');location.href='session.php?do=exit'</script>";
		exit;
	}else{
			echo "<script>alert('请输入完整信息');location.href='editpwd.php'</script>";
			exit;		
	}
	
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<title>密码修改 - <?php echo $config['sitename']?></title>
<meta name="keywords" content="<?php echo $config['sitename']?>,密码修改" />
<meta name="description" content="<?php echo $config['sitename']?>密码修改">
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
		<form action="editpwd.php" method="post">
		<input type="hidden" value="<?php echo $session['id']?>" name="uid">
			<div class="item">
				<input value="" class="txt-input txtpd" name="pass_old"  placeholder="原密码" type="text" />
			</div>
			<div class="item">
				<input value=""  name="pass_new" class="txt-input txtpd" placeholder="新密码" type="text" />
			</div>
			<div class="item">
				<input value=""  name="pass_new1" class="txt-input txtpd" placeholder="确认新密码" type="text" />
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
