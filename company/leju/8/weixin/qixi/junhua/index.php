<?php
include("./db.class.php");
ini_set('display_errors', 0);
if ($_GET['id']) {
    $db_config['hostname'] = "localhost";
    $db_config['username'] = 'root';
    $db_config['password'] = 'male365@qq.com';
    $db_config['database'] = 'fuke';
    $db_config['charset'] = "utf8";
    $db = new mysqldb($db_config);
    $id = (int) trim($_GET['id']);

    $sql = "select * from members_qx where id={$id} limit 1";
    $res = mysql_query($sql);
    $row = mysql_fetch_array($res, MYSQL_ASSOC);
}
?>
<!DOCTYPE html>
<html class="">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <title>七夕 - 君悦华庭</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="format-detection" content="telephone=no">
        <link rel="stylesheet" type="text/css" href="img/style.css">
        <SCRIPT type=text/javascript src="img/jquery-1.7.2.min.js"></SCRIPT>
        <script src="img/swipe.js"></script> 
    </head>
    <body>
        <section class="warp">
            <article>
                <div class="cont">
                    <h2>君悦华庭  83827777</h2>
                    <?php if ($row['img']) { ?>
                        <img src="<?php echo $row['img'] ?>" style="max-width: 300px;max-height: 300px;"/>
                    <?php } ?>
                    <form method="post" enctype="multipart/form-data" action="index2.php" class="mt10">
                        <input type="button"  value="点击上传图片" id='upload_file_btn'>
                        <input type="text" name="name" placeholder="姓名" id='name' value="<?php echo $row['name'] ?>" class="mt10">
                        <input type="file" name="file" placeholder="上传图片" id='upload_file' style="display: none">
                        <input type="text" name="tel" placeholder="手机号码" class="mt10" id='tel' value="<?php echo $row['tel'] ?>">
                        <input type="button" id="submit_btn" value="提交" class="mt10" <?php if ($row) { ?> disabled="disabled" <?php } ?>/>
                        <input type="button" value="活动规划"  class="mt10"/>
                    </form>
                </div>
            </article>
            <footer>
                <p style="width:92%; margin:0 4%;  height:46px;line-height:46px; font-size:25px; font-weight:bold "id='res'>
                    <img src="./img/footer.png" />
                </p>
            </footer>
        </section>
        <script>
            $("#upload_file_btn").click(function () {
                $("#upload_file").trigger("click");
            })
            $("#submit_btn").click(function () {
                var name = $("#name").val();
                var tel = $("#tel").val();
                if (!/^((\d{3}-\d{8}|\d{4}-\d{7,8})|(1[3|5|7|8][0-9]{9}))$/.test(tel)) {
                    alert('手机号码不正确');
                    return false;
                }
                if (name == '') {
                    alert('请填写姓名');
                    return false;
                }
                $('form').submit();
//                $.ajax({
//                    url: 'index2.php',
//                    type: 'post',
//                    data: 'name=' + name + '&gender=' + gender + '&birthyear=' + birthyear + '&birthmon=' + birthmon + '&birthmon=' + birthmon + '&tel=' + tel + '&biaobao=' + biaobao,
//                    async: false, //默认为true 异步     
//                    dataType: 'json',
//                    error: function () {
//                        alert('error');
//                    },
//                    success: function (data) {
//                        if (data.res == 'success') {
//                            $("#res").html(data.biaobao);
//                        } else if (data.res == 'system_error') {
//                            alert('系统错误');
//                        } else if (data.res == 'exist') {
//                            alert('已经测试过');
//                            $("#res").html(data.biaobao);
//                        } else {
//                            alert('系统错误');
//                        }
//                    }
//                });
            })
        </script>
    </body>