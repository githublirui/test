<?php
require('conn.php');
require('session.php');
require('functions.php');
$type_arr=explode(',',$config['UserAddArticleType']);
//开启前台发布
if($config['UserAddArticle']==0){
		echo "<script>alert('前台会员不支持发布文章');location.href='ucenter.php'</script>";
		exit;	
}
//微信文章导入
if($_POST){
	require('QueryList.class.php');
	$long=guolv(trim($_POST['long']));
	$type_id=guolv(trim($_POST['type_id']));
	$html=get_contents($long);
	$money=$type_arr[2];
	$html=str_replace('data-src','src',$html);
	$caiji = array(
		"title"=>array(".rich_media_title:first","text"),
		"content"=>array("#js_content","html"),
		
		);
	$quyu='';
	$hj = QueryList::Query($html,$caiji,$quyu);
	$arr = $hj->jsonArr;
	$title=$arr[0]['title'];
	$content=$arr[0]['content'];
	$pic=cut($html,'var msg_cdn_url = "','"');
	if(url_exists($long)==1){
		echo "<script>alert('网址不存在');location.href='weixin.php'</script>";
		exit;		
	}
	if(is_numeric($type_id)==false){
		echo "<script>alert('分类不存在');location.href='weixin.php'</script>";
		exit;		
	}	
	$row=$mysql->query("select * from `article` where `title`='{$title}' limit 1");
	if(!$row){
		$arr=array(
			'top'=>0,
			'title'=>$title,
			'content'=>$content,
			'pic'=>'[weixin]'.$pic,
			'type'=>$type_id,
			'pv'=>0,
			'pv_max'=>'',
			'money'=>$money,
			'day'=>date("Y-m-d",time()),
		);
		$value=arr2s($arr);
		$mysql->query("insert into `article` {$value}");
		$id=mysql_insert_id();
		if($id!==0){
			echo "<script>alert('发布成功');location.href='{$site}/detail.php?aid={$id}&uid={$session['id']}'</script>";
			exit;
		}else{
			echo "<script>alert('发布错误');location.href='weixin.php'</script>";
			exit;			
		}
	}else{
		echo "<script>alert('文章已经存在');location.href='weixin.php'</script>";
		exit;		
	}
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<title>微信文章导入 - <?php echo $config['sitename']?></title>
<meta name="keywords" content="<?php echo $config['sitename']?>,微信文章导入" />
<meta name="description" content="<?php echo $config['sitename']?>微信文章导入">
<script type="text/javascript" src="<?php echo $site?>/static/jquery.js"></script>
<link href="<?php echo $site?>/static/all.css" type="text/css" rel="stylesheet" media="all">
<style>
body{margin:0;}
*{box-sizing:border-box;}
input{font-size: 16px;line-height: 1.25em;outline: 0px none;text-decoration: none;margin:0;}
</style>
</head>

<body class="mhome">

<?php include('header.php')?>

<div class="common-wrapper">
	<div style="padding:10px;text-align:center;margin:20px 20px 0;background:#fff;font-size:16px;border:1px dashed #f00;">复制微信公众号文章链接提交，自动转换分享链接</div>
	<div class="main">
		<form action="weixin.php" method="post">
			<div class="item">
				<input value="" class="txt-input txtpd" name="long" value="" placeholder="请输入微信公众号文章网址" type="text" />
			</div>	
			<div class="item">
			<select name="type_id" class="txt-input txtpd">	
			<option value="<?php echo $type_arr[0]?>"><?php echo $type_arr[1]?></option>
            </select>				
			</div>				
			<div class="item item-btns"> 
			<input type="submit" value="提交" class="btn-login">
			</div>
		</form>
	</div>
</div>


</body>
</html>