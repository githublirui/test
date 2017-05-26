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
<title>我的下线 - <?php echo $config['sitename']?></title>
<meta name="keywords" content="<?php echo $config['sitename']?>下线" />
<meta name="description" content="<?php echo $config['sitename']?>下线">
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
    <td align="center" style="font-size:18px;font-weight:bold;color:#dc2635;">我的下线</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
	<td align="center">注册时间</td>
    <td align="center">用户ID</td>
    <td align="center">收益</td>	
  </tr>
<?php
$rows=$mysql->query("select * from `userdata` where `tj_id`='{$session['id']}' order by -`money`");
foreach($rows as $row){
$time=date("m-d H:i",$row['time']);
print <<<tr
    <tr>
	<td align="center">{$time}</td>
    <td align="center">{$row['id']}</td>
    <td align="center">{$row['money']}</td>
  </tr>
tr;
	
}
?>
</table>
<br><br><br>
<?php include('footer.php')?>
</body>
</html>
