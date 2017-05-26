<?php
require('conn.php');
require('session.php');
require('functions.php');
//print_r($session);

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<title>个人累计收益 - <?php echo $config['sitename']?></title>
<meta name="keywords" content="<?php echo $config['sitename']?>,个人收益" />
<meta name="description" content="<?php echo $config['sitename']?>个人累计收益明细。">
<link href="<?php echo $site?>/static/all.css" type="text/css" rel="stylesheet" media="all">
<style>
td {
	border-bottom: 1px solid #ddd;
	line-height: 24px;
	font-size: 14px;
	color: #333;
	padding:5px 0;
}
.mhome{background:#fff;}
</style>
</head>

<body class="mhome">
<?php include('header.php')?>
<div style="width:96%;margin:0 auto;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" style="font-size:18px;font-weight:bold;color:#dc2635;">最近收益</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="20%" align="center">时间</td>
    <td align="center">来源</td>
	<td align="right"> 收益</td>
  </tr>
<?php
$row_referer=$mysql->query("select * from `refererdata` where `uid`='{$session['id']}' order by `id` desc limit 0,300");
foreach($row_referer as $v_referer){
$time=date("m-d H:i",$v_referer['time']);
print <<<table
  <tr>
    <td width="20%" align="center">{$time}</td>
    <td align="center">{$v_referer['title']}</td>
	<td align="right"><font color=red>{$v_referer['money']}</font></td>
  </tr>
table;
}
?>  
  </table>

</div>
<br><br><br>
<?php
include('footer.php');
?>

</body>
</html>
