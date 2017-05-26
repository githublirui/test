<?php
require('conn.php');
//require('session.php');
require('functions.php');
//print_r($userdata);
//noagent();
//全站访问
if($config['fangwen']==4){
	exit('网站正在更新');
}	

$sever=$_SERVER['SERVER_NAME'];
$aid=guolv($_GET['aid']);//文章id
$uid=guolv($_GET['uid']);//用户id
$day=date("Y-m-d",time());
$long=$_SERVER["HTTP_REFERER"];
$ip=GetIP();
$_ip=explode('.',$ip);
$ip2=$_ip[0].'.'.$_ip[1];//2段ip
$ip3=$_ip[0].'.'.$_ip[1].'.'.$_ip[2];//3段ip
//访问限制
if($config['fangwen']==1){
	if(is_mobile()==false){
		exit('仅限手机访问');
	}
}elseif($config['fangwen']==2){
		if(is_weixin()==false){
			exit('仅限微信访问');
		}
}else{
}

if(is_numeric($aid)){
	$row=$mysql->query("select * from `article` where `id` in({$aid}) limit 1");
	if($row){
			$data=$row[0];
			$顶部广告=$mysql->query("select * from `addata` where `ad_type`='顶部广告' limit 1");//广告
			$底部广告=$mysql->query("select * from `addata` where `ad_type`='底部广告' limit 1");//广告
			$统计代码=$mysql->query("select * from `addata` where `ad_type`='统计代码' limit 1");//广告
			//$微信广告=$mysql->query("select * from `addata` where `ad_type`='微信广告' limit 1");//广告
			$悬浮广告=$mysql->query("select * from `addata` where `ad_type`='悬浮广告' limit 1");//广告
			//统计
		}else{
			exit('文章已过期');			
		}
}else{
	_location($site,301);
	exit;
}
// setcookie("sdone");
// print_r($_COOKIE);
?>
<span style="display:none"><img src="http://img.users.51.la/5062132.asp" style="border:none" /></span>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<title><?php echo $data['title']?></title>
<meta name="keywords" content="<?php echo $data['title']?>"/>
<meta name="description" content="<?php echo $data['title']?>"/>
<link rel="stylesheet" type="text/css" href="static/hz.css" media="all">


<?php 
$_pic=str_replace('[weixin]','',$data['pic']);
?>

<div class="fenxiang" id="fg1" onclick="z_yc()" style="display:none"> </div>
<script>
            function z_yc(){
                $("#fg1").css("display","none");
            }
            function z_fx(){
                $("#fg1").css("display","");
            }
        </script>
<div class="mhome" style="font-family:'STXihei';"><div style="height:0;overflow:hidden;"><img src="http://img01.store.sogou.com/net/a/04/link?appid=100520031&w=710&url=<?php echo $_pic?>"></div>



<style type="text/css">
#y-iframe{
	position:fixed;
	_position:absolute;
	left:0;
	bottom:0;
	_top:expression(eval(document.documentElement.scrollTop+document.documentElement.clientHeight-this.offsetHeight-(parseInt(this.currentStyle.marginTop,10)||0)-(parseInt(this.currentStyle.marginBottom,10)||0)));
	z-index:99999;
	padding-top:1px;
	width:100%;
	min-height:81px;
	height:auto;
	_height:81px;
	
}
.iframe-wrap{
	position:relative;
	margin:0 auto;
	width:1003px;
	height:81px;
}
</style>

</head>
<body class="mhome" style="font-family:'STXihei';">
<div class="article">
  <h1><?php echo $data['title']?></h1>

  <p class="art_txt sau"><span>分享累计收益<a href="reg.php?uid=<?php echo $uid?>"><font color=red><?php echo $data['pv']*$data['money']?></font></a>元&nbsp;&nbsp;<?php
					$row_type=$mysql->query("select * from `typedata` where `id`='{$data['type']}' limit 1");
				?>
				<?php echo ubb($row_type[0]['type_author'])?></span></p>
  <div id="tConArt" class="art_co sau">
						
				<?php 
				if($顶部广告[0]['endtime']!==''){
					if(strtotime($顶部广告[0]['endtime'])>=time()){
						echo $顶部广告[0]['ad_content'];
					}
				}
				?>
				
				
				<div class="rich_media_content" id="js_content">
				<div class="bdsharebuttonbox" style="text-align:center"><a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a><a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a><a href="#" class="bds_tqq" data-cmd="tqq" title="分享到腾讯微博"></a><a href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a><a href="#" class="bds_tqf" data-cmd="tqf" title="分享到腾讯朋友"></a></div>

				<!--内容-->
						<div id="neirong">
						<?php echo $data['content']?>
						</div>
				<?php 
				if($底部广告[0]['endtime']!==''){
					if(strtotime($底部广告[0]['endtime'])>=time()){
						echo $底部广告[0]['ad_content'];
					}
				}
				?>					
		

				</div>


</div>
<script>
var u = navigator.userAgent, app = navigator.appVersion;
var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1;
var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/);
if(isAndroid){
	document.write("<script type=\"text/javascript\" src=\"<?php echo $site?>/static/zepto.js\" ><\/script>");
}else{
	document.write("<script type=\"text/javascript\" src=\"<?php echo $site?>/static/jquery-1.10.2.min.js\" ><\/script>");
}
</script>
<div id="y-iframe">
		<div class="iframe-wrap">
		<div id="dy_02">	
		</div>
		</div>
</div>
<div style="display:none;" id="div_dy_02">
				<?php 
				if($悬浮广告[0]['endtime']!==''){
					if(strtotime($悬浮广告[0]['endtime'])>=time()){
						echo $悬浮广告[0]['ad_content'];
					}
				}
				?>	
</div>
<script>
document.getElementById("dy_02").innerHTML=document.getElementById("div_dy_02").innerHTML;document.getElementById("div_dy_02").innerHTML="";
</script>
<!-- more -->
<div style="margin:10px auto 0px;width:90%;padding-bottom:5px;border-bottom:1px solid #c8c7cc;font-family:'微软雅黑'" align="center"><a href="list.php?uid=<?php echo $uid?>">更多内容>></a></div>
<div class="list">
<?php
if(is_numeric($config['DetailMoreNum'])==true && $config['DetailMoreNum']>0){
$rows_more=$mysql->query("select * from `article` order by -`pv` limit 0,{$config['DetailMoreNum']}");
foreach($rows_more as $v_more){
	$pic1=str_replace('[weixin]','',$v_more['pic']);
print <<<table
		<div class="list_d">
    	<a class="list_dd bor_b" href="{$site}/detail.php?aid={$v_more['id']}&uid={$uid}" target="_blank">
		<div class="list_l"><img src="{$pic1}" width="135" height="90"/></div>
		<div class="list_lt">
			<dl class="list_ld">
				<dt>{$v_more['title']}</dt>
				<dd>阅读量：{$v_more['pv']} <font color=red>  点击分享赚{$v_more['money']}元</font></dd>
			</dl>
		</div>
		</a>
		</div>
table;
}
}
?>
</div>
<?php
			//统计
			if(is_mobile() && is_numeric($uid)==true){
				//cookie防御
				if($_COOKIE['sdone']!==99){
					//3段ip相似度过滤
					$row_ip3=$mysql->query("select `id` from `refererdata` WHERE  `ip` LIKE  '%{$ip3}%'  and `day`='{$day}' limit 1");
					if(!$row_ip3){
						//setcookie("day",$day, time()+3600*24);//洒种子 24小时过期
						?>
						<script>cookie_set("sdone",99);</script>
						<?
						//2段ip匹配用户
						$row_ip2=$mysql->query("select `id` from `refererdata` WHERE  `ip` LIKE  '%{$ip2}%'  and `day`='{$day}' and `uid`='{$uid}' limit 1");
						if(!$row_ip2){
							//文章浏览pv
							$mysql->query("update `article` set `pv`=`pv`+1 where `id` in({$aid}) limit 1");	
							//扣量
							if(is_numeric($config['kou_pr'])==true && $config['kou_pr']!==0){
								$row_user=$mysql->query("select * from `userdata` where `id`='{$uid}' limit 1");
								if($row_user[0]['kou']<$config['kou_pr'] && $row_user[0]['kou']>=0){
									$mysql->query("update `userdata` set `kou`='100' where `id` in({$uid}) limit 1");	
									$kouliang=1;//开始扣量
									$kou_arr=array(
										'uid'=>$uid,
										'aid'=>$aid,
										'title'=>$data['title'],
										'long'=>$long,
										'money'=>$data['money'],
										'ip'=>$ip,
										'day'=>$day,
										'time'=>time(),										
									);
									$value_kou=arr2s($kou_arr);
									$mysql->query("insert into `koudata` {$value_kou}");
									//注册多少天开始扣量
									if(is_numeric($config['kou_day'])==true){
										if($time-$config['kou_day']*86400<$row_user[0]['time']){
											$kouliang=0;//不扣量
										}else{
											//扣量时间选择
											if($config['kou_hour']!==''){
												$arr_kou_hour=explode(',',$config['kou_hour']);
												$h=date("H",time());//小时
												if(in_array($h,$arr_kou_hour)){
													$kouliang=1;//开始扣量
												}else{
													$kouliang=0;//不扣量
												}
											}
										
										}				
									}
								}else{
									$mysql->query("update `userdata` set `kou`=`kou`-'{$config['kou_pr']}' where `id` in({$uid}) limit 1");	
									$kouliang=0;//不扣量
								}
							}
							if($kouliang==0){
								//不扣量
								$arr_referer=array(
									'uid'=>$uid,
									'aid'=>$aid,
									'title'=>$data['title'],
									'long'=>$long,
									'money'=>$data['money'],
									'ip'=>$ip,
									'day'=>$day,
									'time'=>time(),
								);
								$value_referer=arr2s($arr_referer);
								$mysql->query("insert into `refererdata` {$value_referer}");
								$mysql->query("update `userdata` set `money`=`money`+'{$data['money']}' where `id` in({$uid}) limit 1");
								setcookie("ivid",$uid, time()+3600*24);
							}					
						}
					}				
				}
			}
?>
<script>
    $(function() {
        var pattern = /^http:\/\/mmbiz/;
        var prefix = 'http://img01.store.sogou.com/net/a/04/link?appid=100520031&w=710&url=';
        $("img").each(function(){
            var src = $(this).attr('src');
            if(pattern.test(src)){
                var newsrc = prefix+src;
                $(this).attr('src',newsrc);
            }
			//$('#js_content').autoIMG();
        });
    });
</script>
<?php
if($config['DetailPvOpen']==1){
	echo "<p>阅读数：{$data['pv']}</p>";
}else{
	echo "<p>阅读数：{$config['DetailPvOpen']}</p>";
}
?>

<script>
function cookieRead(a,b,c,g){if(void 0==b){a+="=";b=document.cookie.split(";");c=0;for(g=b.length;c<g;c++){for(var i=b[c];" "==i.charAt(0);)i=i.substring(1,i.length);if(0==i.indexOf(a))return decodeURIComponent(i.substring(a.length,i.length))}return null}}
function cookie_set(key,value){
var Then=new Date();
var xdomain="."+gettopdomain(document.location.href);
Then.setTime(Then.getTime()+24*60*60*1000);
document.cookie=key+"="+value+"; path=/; domain="+xdomain+"; expires="+Then.toGMTString();
}
function gettopdomain(url){
url=url.replace(/http:\/\/.*?([^\.]+\.(com\.cn|org\.cn|net\.cn|[^\.]+))\/.+/, "$1")+"/test";
url=url.split("/")[0];
return url;
}
//cookie_set("sdone",99);
//alert(cookieRead("sdone"));
</script>
<?php 
if($统计代码[0]['endtime']!==''){
	if(strtotime($统计代码[0]['endtime'])>=time()){
		echo $统计代码[0]['ad_content'];
	}
}
?>
<?php 
$mysql->__destruct();
$mysql->close();
?><br><br><br>
</body>
</html>
