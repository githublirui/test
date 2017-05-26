<?php
session_start();
require('conn.php');
require('functions.php');
if($_SESSION['login']==1){
	_location("ucenter.php",301);
	exit;
}

function tpl_send_sms($apikey, $tpl_id, $tpl_value, $mobile){
	$url="http://yunpian.com/v1/sms/tpl_send.json";
	$encoded_tpl_value = urlencode("$tpl_value");  //tpl_value需整体转义
	$post_string="apikey=$apikey&tpl_id=$tpl_id&tpl_value=$encoded_tpl_value&mobile=$mobile";
	return sock_post($url, $post_string);
}

function send_sms($apikey, $text, $mobile){
			$url="http://yunpian.com/v1/sms/send.json";
			$encoded_text = urlencode("$text");
			$post_string="apikey=$apikey&text=$encoded_text&mobile=$mobile";
			return sock_post($url, $post_string);
		}
		function sock_post($url,$query){
			$data = "";
			$info=parse_url($url);
			$fp=fsockopen($info["host"],80,$errno,$errstr,30);
			if(!$fp){
				return $data;
			}
			$head="POST ".$info['path']." HTTP/1.0\r\n";
			$head.="Host: ".$info['host']."\r\n";
			$head.="Referer: http://".$info['host'].$info['path']."\r\n";
			$head.="Content-type: application/x-www-form-urlencoded\r\n";
			$head.="Content-Length: ".strlen(trim($query))."\r\n";
			$head.="\r\n";
			$head.=trim($query);
			$write=fputs($fp,$head);
			$header = "";
			while ($str = trim(fgets($fp,4096))) {
				$header.=$str;
			}
			while (!feof($fp)) {
				$data .= fgets($fp,4096);
			}
			return $data;
		}

//密码找回
if($_POST){
	$phone=guolv(trim($_POST['phone']));
	$code=guolv(trim($_POST['code']));

	if(is_phone($phone)==false){
		echo "<script>alert('请输入正确的手机号');location.href='find.php'</script>";
		exit;
	}
	if($code!==$_SESSION['code']){
		echo "<script>alert('验证码错误，请重新输入');location.href='find.php'</script>";
		exit;			
	}	
	$row=$mysql->query("select * from `userdata` where `phone`='{$phone}' limit 1");
	if($row){
		$rand=rand(1,9999);
		$mysql->execute("update `userdata` set `pass`='{$rand}' where `phone`='{$phone}'");
		$apikey = "5b4aa85957d6b5ce942ac4c9e8e343fe"; //请用自己的apikey代替
		$text="正在找回密码，您的密码是#{$rand}#";
		send_sms($apikey,$text,$phone);		
		echo "<script>alert('请查收手机短信中的新密码');location.href='login.php'</script>";
		exit;			
	}else{
		echo "<script>alert('手机号码错误');location.href='find.php'</script>";
		exit;		
	}
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<title>找回密码 - <?php echo $config['sitename']?></title>
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
	<div style="padding:10px;text-align:center;margin:20px 20px 0;background:#fff;font-size:16px;border:1px dashed #f00;">密码找回</div>
	<div class="main">
		<form action="find.php" method="post">
			<div class="item">
				<input value=""  name="phone" class="txt-input txtpd" placeholder="请输入注册手机号" type="text" />
			</div>
			<div class="item">
				<input style="width: 40%;" name="code" type="text" class="txt-input txtpd" placeholder="请输入验证码" />
				<img src="code.php?<?php echo rand(1,99)?>">
			</div>				
			<div class="item item-btns"> 
			<input type="submit" value="提交" class="btn-login">
			</div>
		</form>
		<div class="item item-login-option">
		<span class="register-free"><a rel="nofollow" href="<?php echo $site?>/login.php">登录</a></span>
		</div>
	</div>
</div>


</body>
</html>