<!doctype html>
<html>
<meta charset="utf-8">
<?php
session_start();
require('conn.php');
require('functions.php');
//全站访问
if($config['fangwen']==4){
	exit('网站正在更新');
}	
if($_SESSION['login']==1){
	_location("ucenter.php",301);
	exit;
}

$ip=GetIP();

//地区限制
if($config['area']!=='全国'){
	require('ipclass.php');
	$iplocation = new IpLocation();   
	$location = $iplocation->getlocation($ip);   
	$country=to_utf8($location['country']);
	$config_country=$config['area'].$config['city'];
	$country2=str_replace('地区','',$config_country);
	if($country!==$country2){
		exit('当前城市非推广地区，禁止注册');
	}
}


//注册
$tj_id=guolv(trim($_POST['tj_id']));//推荐人
if(is_numeric($tj_id)){
	$row_tj=$mysql->query("select * from `userdata` where `id` in({$tj_id})");
	if(!$row_tj){
		$tj_id=0;
	}
}else{
	$tj_id=0;
}
if($_POST){
	$phone=guolv(trim($_POST['username']));
	$pass=guolv(trim($_POST['password']));
	// $code=guolv(trim($_POST['code']));
	$yzm=guolv(trim($_POST['yzm']));
	$yzm1 = substr($phone,3,4);
	if($config['openreg']==0){
		echo "<script>alert('注册关闭，请联系我们');location.href='reg.php'</script>";
		exit;		
	}
	if($config['ipreg2']==1){
		$row_ip=$mysql->query("select * from `userdata` where `ip`='{$ip}' order by `id` limit 1");//获取相同ip
		if($row_ip){
			echo "<script>alert('为防止同一人注册多个号,禁止同一ip重复注册!');location.href='reg.php'</script>";
			exit;			
		}	
	}	
	if(is_phone($phone)==false){
		echo "<script>alert('请输入正确的手机号');location.href='reg.php'</script>";
		exit;
	}
	if($config['yunpian']!==''){
		if($yzm!==$yzm1){
			echo "<script>alert('手机验证码不正确');location.href='reg.php'</script>";
			exit;
		}
	}	
	if($pass==''){
		echo "<script>alert('请输入密码');location.href='reg.php'</script>";
		exit;		
	}
	// if($code!==$_SESSION['code']){
		// echo "<script>alert('验证码错误，请重新输入');location.href='login.php'</script>";
		// exit;			
	// }	
	$row=$mysql->query("select * from `userdata` where `phone`='{$phone}' limit 1");
	if(!$row){
		$arr=array(
			//'id'=>null,
			'tj_id'=>$tj_id,
			'phone'=>$phone,
			'pass'=>$pass,
			'money'=>$song,
			'wx'=>'',
			'realname'=>'',
			'alipay'=>'',
			'wgateid'=>'',
			'ip'=>$ip,
			'kou'=>100,
			'day'=>date("Y-m-d",time()),
			'time'=>time(),
		);
		$value=arr2s($arr);
		$mysql->query("insert into `userdata` {$value}");
		//$mysql->query("INSERT INTO `userdata` (`id`, `tj_id`, `phone`, `pass`, `money`, `wx`, `realname`, `alipay`, `wgateid`, `ip`, `kou`, `day`, `time`) VALUES (NULL, '{$tj_id}', '{$phone}', '{$pass}', '{$song}', '', '', '', '', '{$ip}', '100', '{$day}', '{$time}');");	
		$id=mysql_insert_id();
		if($id!==0){
			//师傅获得多少奖励
			if($config['reg_yzr1']!==0 && $tj_id!==0){
				$mysql->execute("update `userdata` set `money`=`money`+'{$config['reg_yzr1']}' where `id`='{$tj_id}'");
				//写入状态
					$arr_reg_yzr1=array(
						'uid'=>$tj_id,
						'aid'=>'',
						'title'=>"新收徒弟ID：{$id}发放奖励",
						'long'=>'#',
						'money'=>$config['reg_yzr1'],
						'ip'=>$ip,
						'day'=>$day,
						'time'=>time(),
					);
				$value_reg_yzr1=arr2s($arr_reg_yzr1);
				$mysql->query("insert into `refererdata` {$value_reg_yzr1}");
			}
			echo "<script>alert('注册成功！请重新登录');location.href='login.php'</script>";
			exit;
		}else{
			echo "<script>alert('注册失败');location.href='reg.php'</script>";
			exit;			
		}
	}else{
		echo "<script>alert('手机号已经被占用');location.href='reg.php'</script>";
		exit;		
	}
}
?>
<head>
<meta name="viewport" content="width=device-width,initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<title>注册 - <?php echo $config['sitename']?></title>
<meta name="keywords" content="<?php echo $config['sitename']?>,用户注册。" />
<meta name="description" content="<?php echo $config['sitename']?>用户注册。">
<script type="text/javascript" src="<?php echo $site?>/static/jquery.js"></script>
<link href="<?php echo $site?>/static/all.css" type="text/css" rel="stylesheet" media="all">
<style>
body{margin:0;}
*{box-sizing:border-box;}
input{font-size: 16px;line-height: 1.25em;outline: 0px none;text-decoration: none;margin:0;}
</style>
</head>
<script type="text/javascript">
$(document).ready(function(){
	var InterValObj; //timer变量，控制时间
	var count = 5; //间隔函数，1秒执行
	var curCount=60;//当前剩余秒数	
	
  $("#btnSendCode").click(function(){
	var phone=document.getElementById("username").value; 
	if (!phone.match(/^(((1[3|4|5|7|8][0-9]{1}))+\d{8})$/)) {
		alert("手机号不正确");
		document.getElementById('username').focus();
		return;
	}
	var password=document.getElementById("password").value; 
	if(password==''){
		alert("请输入密码");
		document.getElementById('password').focus();
		return;		
	}
    var ifrm = document.getElementsByName('iframe')[0];  
    ifrm = document.getElementById('iframe');  
    ifrm.src = 'reg_yzm.php?phone='+phone;
	$("#btnSendCode").attr("disabled", "true");
	$("#btnSendCode").val("请在" + curCount + "秒内输入验证码");
	InterValObj = window.setInterval(SetRemainTime, 1000); //启动计时器，1秒执行一次	
  });
 //timer处理函数
function SetRemainTime() {
            if (curCount == 0) {                
                window.clearInterval(InterValObj);//停止计时器
                $("#btnSendCode").removeAttr("disabled");//启用按钮
                $("#btnSendCode").val("重新发送验证码");
				curCount=60;
            }
            else {
                curCount--;
                $("#btnSendCode").val("请在" + curCount + "秒内输入验证码");
            }
        }
});		
</script> 
<body>

<header style="min-height:44px;">
  <div class="logo"></div>
</header>
<iframe name="iframe" style="display: none" id="iframe"></iframe> 	  
<div class="common-wrapper">
	<div style="padding:10px;text-align:center;margin:20px 20px 0;background:#fff;font-size:16px;border:1px dashed #f00;">已经发放<a style="color:#f00;font-weight:bold;"> 24787500 </a>元奖金</div>
	<div class="main">
		<form action="reg.php" method="post">
		<input type="hidden" value="<?php echo $_GET['uid']?>" name="tj_id">
			<div class="item">
				<input value="" class="txt-input txtpd" name="username" id="username" value="" placeholder="请输入手机号" type="text" />
			</div>
			<div class="item">
				<input value="" id="password" name="password" class="txt-input txtpd" placeholder="请输入密码" type="password" />
			</div>
			<!--
			<div class="item">
				<input style="width: 40%;" name="code" id="code" type="text" class="txt-input txtpd" placeholder="请输入验证码" />
				<img src="code.php?<?php echo rand(1,99)?>">
			</div>
			-->
			<?php
			if($config['yunpian']!==''){
			?>
			<div class="item">
				<input style="width: 40%;"  name="yzm" class="txt-input txtpd" placeholder="请输入手机验证码" type="text" />
				<input id="btnSendCode" name="btnSendCode" type="button" value="发送验证码" onclick="btnSendCode()"/>
			</div>
			<div style="font-size:14px;line-height:24px;border:1px dashed #f00;background:#fff;padding:5px;margin-bottom:20px;">每天最多只能发送5条验证码哦，点击发送验证码后请耐心等待！！切勿重复点击！！</div>			
			<?php }?>
			<div class="item item-btns"> 
			<input type="submit" value="注册" class="btn-login">
			</div>
			<div class="item item-btns"> 
			<a href="login.php" class="btn-login">已有帐号，点我登录</a>
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
		<!--<span class="register-free"><a rel="nofollow" href="<?php echo $site?>/login.php">登录</a></span>-->
		<!--<span class="retrieve-password"><a href="<?php echo $site?>/find.php">找回密码</a></span>-->
		</div>
	</div>
</div>


</body>
</html>