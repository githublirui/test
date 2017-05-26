<?php
require('conn.php');
require('session.php');
require('functions.php');
//print_r($userdata);
if($userdata['phone']==''){
	echo "<script>alert('请填写手机号码用于登录帐号');location.href='zl.php'</script>";
	exit;	
}
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>个人中心 - <?php echo $config['sitename']?></title>
<meta name="keywords" content="<?php echo $config['sitename']?>,个人中心,用户中心" />
<meta name="description" content="<?php echo $config['sitename']?>个人中心。">
<meta name="viewport" content="width=device-width,initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta name="format-detection" content="telephone=no" />
<script type="text/javascript" src="static/zepto.js"></script>
<link href="static/all.css" type="text/css" rel="stylesheet" media="all">
</head>

<body class="mhome">

<!--
<header>
	<div class="logo"></div>
</header>
-->
<?php include('header.php');?>
    
<div class="money">
	<p class="userId">转客<?php echo $userdata['id']?>的收益余额</p>
	<p class="userUm">
                    <a href="referer.php"><?php echo $userdata['money']?>元</a>
            </p>
</div>
    
<div class="profit" style="height:auto;">
	<p class="bor"><a href="referer.php">个人累计收入（元）<b style="color:#f00;"><?php echo $userdata['money']?></b> (查看明细)</a></p>
	<!--<p class="bor"><a href="#">邀请累计收入（元）<b style="color:#f00;">0.00</b></a></p>-->
</div>
<?php
if($config['UserAddArticle']==1){
?>
<div class="gonggao">
	<p><a href="weixin.php">发布文章</a></p>
</div>
<?php }?>
<nav>
	<ul class="clearfix borT">
    	<li><a class="a1" href="<?php echo $site?>/news.php">新手学堂</a></li>
        <li>
        					<a class="a2" href="<?php echo $site?>/list.php?uid=<?php echo $session['id']?>">开始赚钱</a>
			        </li>
        <li>
                <a class="a3" href="invite.php?uid=<?php echo $session['id']?>">邀请好友</a>
                </li>
        <li class="nor">
							<a class="a9" href="xia.php">我的下线</a>
                    </li>
    </ul>
    <ul class="clearfix">
    	<li><a class="a5" href="<?php echo $site?>/txlist.php">提现记录</a></li>
 
        	                <li><a class="a13" href="<?php echo $site?>/zl.php">我的资料</a></li>
	
        <li><a class="a7" href="<?php echo $site?>/pai.php">转客排行</a></li>
        <li class="nor">
        	<a class="a4" href="<?php echo $site?>/tx.php">我要提现</a>
        </li>
    </ul>
	<!--
    <ul class="clearfix">
    	<li><a class="a13" href="<?php echo $site?>/zl.php">我的资料</a></li>
        <li><a class="a10" href="#">收藏我们</a></li>
        <li><a class="a11" href="#">关注我们</a></li>
        <li class="nor">
        	<a class="a12" target="_blank" href="http://shang.qq.com/wpa/qunwpa?idkey=513dc4013d1fe39458f3bc349a9e4610f1c4d50376cce0f21479a344a65b9538">加群交流</a>
        </li>
    </ul>	
	-->
</nav>
    
<div class="gonggao">
	<p><a href="session.php?do=exit">退出账户</a></p>
</div>
<br><br><br><br>
    
<?php
include('footer.php');
?>
</body>
</html>