<?php
require('conn.php');
require('session.php');
require('functions.php');
$id=guolv($_GET['id']);
//print_r($userdata);

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<title>新闻-<?php echo $config['sitename']?></title>
<link href="<?php echo $site?>/static/all.css" type="text/css" rel="stylesheet" media="all">
<style>
td{border-bottom:1px solid #ddd;height:40px;line-height:40px;font-size:14px;color:#333;}
.mhome{background:#fff;}
</style>
</head>

<body class="mhome">

<?php include('header.php')?>
<?php
if(is_numeric($id)==false){
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" style="font-size:18px;font-weight:bold;color:#dc2635;">新闻</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<?php
	$rows=$mysql->query("select * from `newsdata` order by `id` limit 0,10");
	foreach($rows as $row){
print <<<tr
		<tr>
		<td width="10px"></td>
		<td align="left"><a href="news.php?id={$row['id']}" target="_blank">{$row['title']}</a></td>
	  </tr>
tr;
		
}
?>
</table>
<?php }else {?>
<div class="common-wrapper">
<div class="main" style="padding-top:0;">
<div style="color:#000;font-size:16px;padding:10px;margin:10px;line-height:24px;">
<?php
$row=$mysql->query("select * from `newsdata` where `id` in({$id}) limit 1");
?>
<span style="color:#f00;font-size:18px;font-weight:bold;"><?php echo $row[0]['title']?></span><br><br>
<?php echo $row[0]['content']?>
</div>
</div></div>
<?php }?>
<br><br><br>
<?php include('footer.php')?>
</body>
</html>
