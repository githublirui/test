<?php
require('conn.php');
require('session.php');
require('functions.php');
//print_r($userdata);

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<title>转客排行 - <?php echo $config['sitename']?></title>
<meta name="keywords" content="<?php echo $config['sitename']?>排行榜,微转排行,微转联盟" />
<meta name="description" content="在这里可以实时查看最新的<?php echo $config['sitename']?>排名。">
<link href="<?php echo $site?>/static/all.css" type="text/css" rel="stylesheet" media="all">
<style>
td{border-bottom:1px solid #ddd;height:40px;line-height:40px;font-size:14px;color:#333;}
.mhome{background:#fff;}
</style>
</head>

<body class="mhome">

<?php include('header.php')?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" style="font-size:18px;font-weight:bold;color:#dc2635;">↑转客排行榜</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center">排名</td>
    <td align="center">用户</td>
    <td align="center">收益</td>
  </tr>
<?php
$rows=$mysql->query("select * from `userdata` order by -`money` limit 0,10");
for($i=0;$i<=10;$i++){
$phone = substr_replace($rows[$i]['phone'],'****',3,4);
print <<<tr
    <tr>
    <td align="center">{$i}</td>
    <td align="center">{$phone}</td>
    <td align="center">{$rows[$i]['money']}</td>
  </tr>
tr;
	
}
?>
</table>
<br><br><br>
<?php include('footer.php')?>
</body>
</html>
