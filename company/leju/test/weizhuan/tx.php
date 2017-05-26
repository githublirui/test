<?php
require('conn.php');
require('session.php');
require('functions.php');
if($userdata['realname']=='' || $userdata['alipay']==''){
		echo "<script>alert('请填写正确收款姓名和支付宝帐号');location.href='zl.php'</script>";
		exit;	
}
//提现金额不足最小提现
// if($userdata['money']<$ti){
		// echo "<script>alert('最低提现金额{$ti}，你的余额不足');location.href='ucenter.php'</script>";
		// exit;		
// }

//提现
if($_POST){
	$money=guolv($_POST['money']);
	$realname=$userdata['realname'];
	$alipay=$userdata['alipay'];
	//$yzm=guolv($_POST['yzm']);
	if($alipay=='' || $realname==''){
		echo "<script>alert('请填写正确收款姓名和支付宝帐号');location.href='zl.php'</script>";
		exit;		
	}
	if(is_zzs($money)==false){
		echo "<script>alert('提现金额必须是整数');location.href='tx.php'</script>";
		exit;
	}
	if($money<$ti){
		echo "<script>alert('最低提现金额{$ti}');location.href='tx.php'</script>";
		exit;
	}	
	if(is_numeric($money)==false){
		echo "<script>alert('提现金额必须是整数');location.href='tx.php'</script>";
		exit;		
	}
	if($money>$userdata['money']){
		echo "<script>alert('提现金额超过你的余额');location.href='tx.php'</script>";
		exit;			
	}
	
	// if($yzm=='' || $yzm!==$_COOKIE['yzm']){
		// echo "<script>alert('你输入的手机验证码错误');location.href='tx.php'</script>";
		// exit;			
	// }
	
	if($realname!=='' && $alipay!==''){
		$arr=array(
			//'id'=>null,
			'uid'=>$userdata['id'],
			'sx1'=>$session['tj_id'],//1级上线
			'realname'=>$realname,
			'phone'=>$userdata['phone'],
			'alipay'=>$alipay,
			'money'=>$money,
			'state'=>0,
			'time'=>time(),
			
		);
		$values=arr2s($arr);
		$mysql->query("update `userdata` set `money`=`money`-{$money} where `id`='{$userdata['id']}'");
		$mysql->execute("insert into `txdata` {$values}");
		$id=mysql_insert_id();
		if($id!==0){
			echo "<script>alert('提现申请成功，等待支付');location.href='txlist.php'</script>";
			exit;			
		}
	}
}
?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<title>申请提现 - <?php echo $config['sitename']?></title>
<meta name="keywords" content="<?php echo $config['sitename']?>,提现,申请提现" />
<meta name="description" content="<?php echo $config['sitename']?>申请提现入口">
<script type="text/javascript" src="static/zepto.js"></script>
<link href="static/all.css" type="text/css" rel="stylesheet" media="all">
<style>
.mhome{background:#fff;}
.main .item .txt-input{width:69%;padding:0 5px;}
</style>
</head>
<script>
function yzm(){
    var ifrm = document.getElementsByName('iframe')[0];  
    ifrm = document.getElementById('iframe');  
    ifrm.src = 'yzm.php';
}
</script>
<body class="mhome">

<?php include('header.php')?>
  
<div class="common-wrapper" style="background:#fff;">
<div class="main" style="padding-top:0;">
  <form class="contact_form" action="tx.php" method="post">
    <div style="font-size:18px;height:30px;line-height:30px;text-align:center;border-bottom:1px solid #ddd;color:#dc2635">支付宝</div>
    <div style="font-size:18px;height:30px;line-height:30px;text-align:center;margin-bottom:10px;">可提现金额：<?php echo $userdata['money']?></div>
    <div class="item item-username" style="text-align:center;">
	
<input class="txt-input txt-username" type="text"  name="money" placeholder="请输入提现整数金额" />

    </div>
   
	<!--
     <div class="item item-username">
      <label style="font-size:16px;">手机验证：</label>
                <input class="txt-input txt-username" type="text"  name="yzm" placeholder="请输入手机验证码" />
    </div>
	
		<div class="item item-btns"> <a class="btn-yzm" onclick="yzm();">获取验证码</a></div>
		-->
		<div style="font-size:14px;line-height:24px;border:1px dashed #f00;background:#fff;padding:5px;margin-bottom:20px;">
		
		请正确填写支付宝和收款姓名，输入错误概不负责！！！
		</div>
        <div class="item item-btns"> <button id="loginSubmit" class="btn-login" >确认</button></div>
       </form>
<iframe name="iframe" style="display: none" id="iframe"></iframe> 	   
</div>
</div>

<br><br><br>
    
<?php include('footer.php')?>
</body>
</html>
