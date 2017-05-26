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
<title>收徒赚钱 - <?php echo $config['sitename']?></title>
<meta name="keywords" content="<?php echo $config['sitename']?>,收徒赚钱,收徒,徒弟" />
<meta name="description" content="<?php echo $config['sitename']?>如何收徒，怎么能靠徒弟增加自己的收益。">
<link href="static/all.css" type="text/css" rel="stylesheet" media="all">
<style>
td {border-bottom: 1px solid #ddd;line-height: 24px;font-size: 14px;color: #333;padding:5px 0;}
.mhome{background:#fff;}
</style>
</head>

<body class="mhome">
<?php include('header.php')?>

<div style="width:96%;margin:0 auto;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="center" style="font-size:18px;font-weight:bold;color:#dc2635;">收徒赚钱</td>
      </tr>
    </table>
    <div style="line-height:26px;margin:10px 0;">
    您的转客码：<a style="color:#f00;font-weight:bold;"><?php echo $session['id']?></a><br>
    您的推广链接：<br>
    <textarea style="width:100%;padding:10px 0;border:2px solid #f00;font-size:14px;"><?php echo $site?>/reg.php?uid=<?php echo $session['id']?></textarea><br>
    您的好友通过您的推广链接注册成功后，系统就自动跟踪统计您的下线提成。<br>
    具体操作方法：<br>
    复制一下信息<br>
    <textarea style="width:100%;padding:10px 0;border:2px solid #f00;font-size:14px;"><?php echo $config['sitename']?>-转发文章就能赚钱，每天5分钟，轻松月入万元，<?php echo $site?>/reg.php?uid=<?php echo $session['id']?></textarea><br>
    1：作为您的QQ签名<br>
    2：发到您的QQ或者微信群<br>
    3：作为您微信签名<br>
    4：发给您的微信好友<br>
    5：放到您的个人网站<br>
    6：还可以选择在各大网站的兼职频道发帖，如豆瓣的兼职小组、百度兼职吧、19楼兼职等，这样你的下线发展会快些。<br>
    只要完成以上任务，您就赚大啦，因为：<br>
    您邀请好友成为您的下线后，您可以长期享受好友阅读和下线收益的20%分红哦。
    </div>
</div>

<br><br><br>
<?php include('footer.php')?>
</body>
</html>
