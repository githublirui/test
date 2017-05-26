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
<title>提现记录 - <?php echo $config['sitename']?></title>
<meta name="keywords" content="<?php echo $config['sitename']?>,提现,提现记录" />
<meta name="description" content="<?php echo $config['sitename']?>提现记录查询">
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
    <td align="center" style="font-size:18px;font-weight:bold;color:#dc2635;">提现记录</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center">时间</td>
    <td align="center">金额</td>
    <td align="center">状态</td>
  </tr>
<?php
$row_tx=$mysql->query("select * from `txdata` where `uid`='{$session['id']}' order by `id` desc");
if($row_tx){
foreach($row_tx as $v_tx){
$time=date("Y-m-d H:i",$v_tx['time']);
if($v_tx['state']==1){
	$state='<font color=green>已支付</font>';
}elseif($v_tx['state']==2){
	$state='<font color=red>支付失败</font>';
}else{
	$state='<font color=blue>审核中</font>';
}
print <<<tr
  <tr>
    <td align="center">{$time}</td>
    <td align="center">{$v_tx['money']}</td>
    <td align="center">{$state}</td>
  </tr>
tr;
}
}else{
?>  
     <tr>	 
	<td align="center" colspan="4" style="font-size:18px;font-weight:bold;color:#dc2635;">您目前还没有提现申请哦！</td>
  </tr>
<?php }?>  
 </table>
<br><br><br>
<?php include('footer.php')?>
</body>
</html>
