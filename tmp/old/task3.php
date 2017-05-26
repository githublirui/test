<?php
//数据库配置
$db_server = 'localhost';
$db_user = 'root';
$db_psw = '';
$db_name = 'chhome';

include("./phpexcel/PHPExcel.php");
include './Utils.class.php';

$action = @$_GET['action'];
$dowload_type = @$_POST['dowload_type'];

if ($action == 'email_search') {
    $emails_text = @$_POST['emails_text'];
    if ($emails_text) {
        $email_strs = explode(";", $emails_text);
        $valid_emails = array();

        //插入数据库
        foreach ($email_strs as $email_str) {

            $pre = '/([^\s]+)\s*[\'\"]?<([\'\"]?[^>]+[\'\"]?)>[\'\"]?\s*\;?/i';
            preg_match_all($pre, $email_str, $match);

            $email_name = str_replace("'", '', @$match[1][0]);
            $email_name = str_replace('"', '', @$email_name);
            $email = str_replace("'", '', @$match[2][0]);
            $email = str_replace('"', '', @$email);

            //验证邮箱
            $email_pa = '/^([^@\s]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i';
            if (preg_match($email_pa, $email)) {
                $valid_emails[] = array($email_name, $email);
                insertTodb($email_name, $email);
            }
        }

        if ($dowload_type == 1) {
            //Excel下载
            include('excel_download.php');
        } else if ($dowload_type == 2) {
            //Csv下载
            include('csv_download.php');
        }
    }
}

function insertTodb($email_name, $email) {
    global $db_server, $db_user, $db_psw, $db_name;
    $link = mysql_connect($db_server, $db_user, $db_psw) or die('connect error');
    mysql_select_db($db_name, $link) or die('select db error');
    mysql_query("SET NAMES UTF8");
    $sql = 'replace into email(username,email,ip,created_at) 
            values("' . $email_name . '","' . $email . '","' . Utils::fetch_alt_ip() . '","' . date("Y-m-d H:i:s") . '")';
    mysql_query($sql);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>中国家-邮箱联系人采集系统</title>
    </head>
    <body>
        <form method="post" action="?action=email_search">
            <textarea name="emails_text" style="height:400px;width:80%;"></textarea>
            <br/>
            <div style="font-size: 12px;color:red;margin-top: 10px;">
                说明: 群邮件联系人采集，文本框内请粘贴群收件人文字，选项框为下载联系人的文件格式支持CSV,Excel,CSV文件可导入到邮箱联系人
            </div>
            <div style="margin-top: 10px;">
                <select name="dowload_type">
                    <option value="1">Excel</option>
                    <option value="2">Csv</option>
                </select>
                <input type="submit" value="确定"/>
            </div>
        </form>
    </body>
</html>