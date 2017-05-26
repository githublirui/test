<?php
require('conn.php');
//require('session.php');
require('functions.php');
//print_r($userdata);

$uid=guolv($_GET['uid']);
if(is_numeric($uid)==false){
	$uid='';
}
?>

<!doctype html>
<html>
        <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
        <title><?php echo $config['sitename']?> - 转发就能赚钱，一次转发，一直赚钱</title>
        <meta name="keywords" content="<?php echo $config['sitename']?>,转发" />
        <meta name="description" content="<?php echo $config['sitename']?>邀请好友转发就赚钱。">
        <link href="<?php echo $site?>/static/reset_v1.css" type="text/css" rel="stylesheet" media="all" />
        <link rel="stylesheet" type="text/css" href="<?php echo $site?>/static/app_v1.css" media="all">
        <style>
.yaoqingbox{width:100%;background-image: -moz-linear-gradient(top, #df2634, #df2634); background-image: -o-linear-gradient(top, #df2634, #df2634);background-image: -webkit-linear-gradient(top, #df2634, #df2634);}
.yaoqingbox .logo{ max-height:360px; max-width:456px; display:block; margin:0 auto; padding-top:66px; padding-bottom:36px;}
.yaoqingbox span{ width:250px; height:82px; background:#FFF; display:block; border-radius:10px; margin:0 auto;}
.yaoqingbox span h3{ border-left:1px solid #d7d7d7; padding:10px 20px; float:right; margin-top:10px; font-size:28px;}
.yaoqingbox span img{ width:40px; height:40px; float:left; margin-left:25px; margin-top:22px;}
.erweima h1{ text-align:center;}
.erweima img{ margin:0 auto;  display:block;}
.fenxiang {
    position: fixed;
    left: 0px;
    top: 0px;
    z-index: 9999;
    width: 100%;
    height: 100%;
    min-width: 320px;
    min-height: 480px;
    background: url('<?php echo $site?>/static/fenxiang.png') no-repeat scroll right top rgba(0, 0, 0, 0.8);}
</style>
        <script type="text/javascript" src="<?php echo $site?>/static/andriod_iphone.js"></script>
        <script>
            if(IsPC()){
                document.write("<script type=\"text/javascript\" src=\"<?php echo $site?>/static/jquery-1.10.2.min.js\" ><\/script>");
            }else{
                document.write("<script type=\"text/javascript\" src=\"<?php echo $site?>/static/zepto.js\" ><\/script>");
            } 
        </script>
        

        </head>
        <body>
        <div style="height:0;overflow:hidden;"><img src="<?php echo $site?>/static/fx20150302.jpg"></div>
<div class="fenxiang" id="fg1" onclick="z_yc()" style="display:none"> </div>
<script>
            function z_yc(){
                $("#fg1").css("display","none");
            }
            function z_fx(){
                $("#fg1").css("display","");
            }
        </script>
        
        
        
        
<div>
          <div class="yaoqingbox"> <a href="<?php echo $site?>"><img class="logo" src="<?php echo $site?>/static/logo2.png" width="200" height="200"></a> <span> <img src="<?php echo $site?>/static/yaoqing.png">
            <h3 onclick="z_fx();" style="margin-top:20px;">立即分享</h3>
            </span>
    <div>
              <p style=" text-align:center;color:#fff;line-height:40px;font-weight:bold;font-size:30px;padding:20px 0;">邀请成功获得<br><?php echo $config['song']?>元奖励<br>小伙伴收益<?php echo $config['tc']?>%永久额外分成</p>
            </div>
  </div>
          <div class="erweima">
    <h1 style="margin:20px auto;">赶快邀请身边小伙伴加入吧</h1>
    <!--<img src="http://qr.liantu.com/api.php?text=<?php echo $site?>/reg.php?uid=<?php echo $session['id']?>">-->
    <p style="text-align:center;font-size:40px;height:40px;line-height:40px;color:#f00;font-weight:bold;"><a style="color:#df2634;" href="<?php echo $site?>/reg.php?uid=<?php echo $uid?>">点击立刻注册</a></p>
  </div>
        </div>

</body>
</html>