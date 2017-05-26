<?php

header("Content-type:text/html;charset=utf-8");

if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST') {
    //上传图片
    include_once('./upload.class.php');
    $upload = Upload::getInstance('./uploads');
    $file = $upload->uploadFile('file');
    $re = $_SERVER['HTTP_REFERER'];
    $host_dir = substr($re, 0, strrpos($re, "/"));
    $file_url = $host_dir . substr($file['path'], 1);

    include("./db.class.php");
    $db_config['hostname'] = "localhost";
    $db_config['username'] = 'root';
    $db_config['password'] = 'male365@qq.com';
    $db_config['database'] = 'fuke';
    $db_config['charset'] = "utf8";
    $db = new mysqldb($db_config);
    $tel = trim(mysql_real_escape_string($_POST['tel']));
    $name = trim(mysql_real_escape_string($_POST['name']));

    if (!$name) {
        echo '<script>alert("姓名不能为空");history.go(-1)</script>';
        exit;
    }
    $pattern = "/^((\d{3}-\d{8}|\d{4}-\d{7,8})|(1[3|5|7|8][0-9]{9}))$/";
    $s = preg_match($pattern, $tel);

    if ($s == 0) {
        echo '<script>alert("手机号不正确");history.go(-1)</script>';
        exit;
    }

    $result = array();
    $members_arr = array(
        'name' => $name,
        'tel' => $tel,
        'img' => $file_url,
        'display' => '2',
        'create_at' => time()
    );
    $db->row_insert('members_qx', $members_arr);
    $uid = mysql_insert_id();
    if ($uid) {
        echo '<script>alert("提交成功");location.href="' . $re . '?a=1&id=' . $uid . '"</script>';
    } else {
        echo '<script>alert("系统错误保存失败");history.go(-1)</script>';
    }
}
?>