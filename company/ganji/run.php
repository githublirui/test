<?php
#数据库设置
$dbhost = "192.168.2.251";
$dbuser = 'test';
$dbpw = 'test';
$dbname = 'zgjw';
$pconnect = 0;
$dbcharset = 'gbk';
$tablepre = '';
error_reporting(0);
//$conn = @mysql_connect($dbhost, $dbuser, $dbpw) or die('E010001'); // 连接数据源
//@mysql_select_db($dbname) or die('E010002'); // 选择数据库

?>
<html>
    <head>
        <title>PHP脚本执行工具</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    </head>
    <body>
        <form method="post" action="#">
            <div style="border: 1px #f60 dashed;padding:5px;width: 500px;">
                <span style="font-size:12px;    ">运行结果：</span><br/>
                <?php
                if ($_POST && $_POST['code']) {
                    eval($_POST['code']);
                }
                ?>
            </div>
            <div style="margin-top: 20px;"> 
                <span style="color:red">&lt;?php</span>
            </div>
            <textarea  name="code" style="height:300px;width:500px;"><?php echo $_POST['code'] ? $_POST['code'] : '' ?></textarea><br/>
            <div style="width: 500px;">
                <input type="submit" value="运行" style="float: right"/>
            </div>
            <div style="clear:both"></div>
        </form>
    </body>
</html>