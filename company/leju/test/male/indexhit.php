<?php
defined('FILE_PATH') or define('FILE_PATH', dirname(__FILE__));

header("Content-type:text/html;charset=utf-8");
ini_set('display_errors', 1);
error_reporting(1);
$num = rand($_POST['sqlmin'], $_POST['sqlmax']);
if (isset($_POST['sqlcount']) && $_POST['count'] == $_POST['sqlcount']) {
    echo "已达到最大执行次数" . $_POST['sqlcount'];
    die;
}
if (isset($_POST['count'])) {
    $_POST['count'] ++;
}
//xnphotosnum,xnhits，
if ($_POST['id']) {
    include(FILE_PATH . "/db.class.php");
    $db_config = array(
        'hostname' => "localhost",
        'username' => 'root',
        'password' => 'male365@qq.com',
        'database' => 'we706',
        'charset' => "utf8"
    );
    if ($_SERVER['HTTP_HOST'] == 'test.local') {
        $db_config = array(
            'hostname' => "localhost",
            'username' => 'root',
            'password' => 'root',
            'database' => 'lrtest',
            'charset' => "utf8"
        );
    }
    $db = new mysqldb($db_config);
    //更新数据
    $newNum = rand($_POST['nummin'], $_POST['nummax']);
    $sql = "update ims_fm_photosvote_provevote set xnhits = xnhits + {$newNum} where id='" . $_POST['id'] . "'";
    $re = mysql_query($sql);
    if (!$re) {
        echo "数据库操作失败" . $sql;
        die;
    }

    if (mysql_affected_rows() == 0) {
        echo "无对应记录";
        die;
    }
}
?>
<body  style="display:block;" onload = "timer(<?php echo $num ?>000)">
    <form method="post" action="" name="uform">
        <div style="margin:0 auto;width:350px;">
            <table>
                <tr>
                    <td colspan="2" style="text-align: center;">虚拟人气</td>
                </tr>
                <tr>
                    <td>ID：</td>
                    <td><input type="text" name="id" value="<?php echo $_POST['id'] ?>"></td>
                </tr>
                <tr>
                    <td>票数/次：</td>
                    <td><input type="text" name="nummin"  size=4 value="<?php echo $_POST['nummin']; ?>">-<input size=4 type="text" name="nummax" value="<?php echo $_POST['nummax']; ?>">次</td>
                </tr>
                <tr>
                    <td>执行间隔：</td>
                    <td><input type="text" name="sqlmin"  size=4 value="<?php echo $_POST['sqlmin']; ?>">-<input size=4 type="text" name="sqlmax" value="<?php echo $_POST['sqlmax']; ?>">秒</td>
                </tr>
                <tr>
                    <td>执行次数：</td>
                    <td><input type="text" name="sqlcount" value="<?php echo $_POST['sqlcount']; ?>"></td>
                </tr>
                <input type="hidden" id= "count" name="count" value="<?php echo $_POST['count'] ?>">
                <?php
                if ($_POST['count']) {
                    ?>
                    <script>
                        function timer(ts) {
                            var ts = ts;
                            var ss = parseInt(ts / 1000);//计算剩余的秒数    
                            ss = checkTime(ss);
                            document.getElementById("timer").innerHTML = ss;
                            ts = ts - 1000;
                            console.log(ts);
                            if (ts > 0) {
                                setTimeout("timer(" + ts + ")", 1000);
                            } else {
                                document.uform.submit();
                            }
                        }
                        function checkTime(i) {
                            if (i < 10) {
                                i = "0" + i;
                            }
                            return i;
                        }
                    </script>
                    <tr>
                        <td colspan=2>已更改<?php echo $_POST['count'] ?>次；此次增加<?php echo $newNum; ?>;<span id = "timer" style="color:red"> </span>秒后开始下次更新</td>
                    <tr>	
                        <?php
                    } else {
                        ?>
                    <tr >
                        <td colspan=2><input type="submit" value="确认"></td>
                    <tr>
                        <?php
                    }
                    ?>
            </table>
        </div>
    </form>
    <?php
    require_once 'cs.php';
    echo '<img src="' . _cnzzTrackPageView(1254383414) . '" width="0" height="0"/>';
    ?>

</body>