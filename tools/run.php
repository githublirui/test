<?php
header("Content-type:text/html;charset=utf-8");

include dirname(__FILE__) . '/../config.php';
error_reporting(E_ERROR);
ini_set('display_errors', 1);

$conn = @mysqli_connect(TEST_DBHOST, TEST_DBUSER, TEST_DBPASS, TEST_DBNAME, TEST_DBPORT) or die('E010001'); // 连接数据源
mysqli_query($conn, "set names " . TEST_DBCHARSET);
if ($_POST) {
    #数据库设置
    $conn = @mysqli_connect(TEST_DBHOST, TEST_DBUSER, TEST_DBPASS, TEST_DBNAME, TEST_DBPORT) or die('E010001'); // 连接数据源
    $code = trim($_POST['para']['code']);
    $data_type = trim($_POST['para']['data_type']);
    if ($code) {
        switch ($data_type) {
            case 'php';
                $code = $code . ';';
                eval($code);
                break;
            case 'json';
                echo '<pre>';
                var_dump(json_decode($code, TRUE));
                echo '</pre>';
                break;
            case 'serialize';
                echo '<pre>';
                var_dump(unserialize($code));
                echo '</pre>';
                break;
            case 'time';
                echo '<pre>';
                $time = $code;
                if (is_numeric($time)) {
                    echo date("Y-m-d H:i:s", $time);
                } else {
                    echo strtotime($time);
                }
                echo '</pre>';
                break;
            case 'sql';
                echo '<pre>';
                $query = $code . ";";
                mysqli_query($conn, "set names " . TEST_DBCHARSET);
                $result = mysqli_query($conn, $query);
                while ($row = mysqli_fetch_assoc($result)) {
                    $ret[] = $row;
                }
                var_dump($ret);
                echo '</pre>';
                break;
        }
    }
    mysqli_close($conn);
    exit;
}
?>
<html>
    <head>
        <title>执行工具</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <script type="text/javascript" src="/js/jquery-1.11.1.min.js"></script>
    </head>
    <body>
        <form method="post">
            <span style="font-size:12px;    ">当前时间： <?php echo date('Y-m-d:H:i:s') . ' / ' . time() ?></span><br/>
            <span style="font-size:12px;    ">运行结果：</span><br/>
            <div style="border: 1px #f60 dashed;padding:5px;width: 500px;" class="res">
            </div>
            <div style="margin-top: 20px;"> 
                <span style="color:red">&lt;?php</span>
            </div>
            <div>
                <input type="radio"  class="data_type" name="data_type" checked="checked" value="php" id="php"/><label for="php">php</label>
                <input type="radio"  class="data_type" name="data_type" value="json" id="json"/><label for="json">json</label>
                <input type="radio"  class="data_type" name="data_type" value="serialize" id="serialize"/><label for="serialize">serialize</label>
                <input type="radio"  class="data_type" name="data_type" value="time" id="time"/><label for="time">time</label>
                <input type="radio"  class="data_type" name="data_type" value="sql" id="sql"/><label for="sql">sql</label>
            </div>
            <textarea  name="code" class="code" style="height:300px;width:500px;"></textarea><br/>
            <div style="width: 500px;">
                <input type="button" class="run" value="运行" style="float: right"/>
            </div>
            <div style="clear:both"></div>
        </form>
        <script>
            $(".run").click(function () {
                var parm = {};
                parm.data_type = $(".data_type:checked").val();
                parm.code = $(".code").val();
                $.ajax({
                    url: '',
                    type: 'post',
                    data: {para: parm},
                    dataType: 'html',
                    success: function (res) {
                        $(".res").html(res);
                    }
                });
            });
        </script>
    </body>
</html>