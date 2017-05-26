<meta charset="utf-8" />
<script src="/js/jquery.js"></script>


<script>
//    #js跨域
    $.ajax({
        url: "zgjwzh.local/",
//        dataType: 'jsonp', // Notice! JSONP <-- P (lowercase)
        success: function(json) {
            // do stuff with json (in this case an array)
            alert("Success");
        },
        error: function() {
            alert("Error");
        },
    });

//    function checkIEVersion() {
//        var ua = navigator.userAgent;
//        var s = "MSIE";
//        var i = ua.indexOf(s)
//        if (i >= 0) {
//            //获取IE版本号 
//            var ver = parseFloat(ua.substr(i + s.length));
//            alert("你的浏览器是IE" + ver);
//        }
//        else {
//            //其他情况，不是IE 
//            alert("你的浏览器不是IE");
//        }
//    }
//    checkIEVersion();
</script>
<?php
/*
$s = array_slice(range(1000000000, 9999999999, rand(0, 8999999899)), rand(0, 8999999999), 100);
var_dump(range(1000000000, 9999999999, rand(0, 8999999899)));
die;
var_dump(md5(md5('123123') . 'a15344'));
die;
$dbhost = "localhost";
$dbuser = 'root';
$dbpw = '';
$dbname = 'zgjw';

$conn = @mysql_connect($dbhost, $dbuser, $dbpw) or die('E010001'); // �������Դ
@mysql_select_db($dbname) or die('E010002'); // ѡ����ݿ�
@mysql_query("set names gbk");

//$sql = "select * from province";
//$query = mysql_query($sql);
//while ($row = mysql_fetch_array($query)) {
//    $result[] = $row['name'];
//}
//echo implode(',', $result);
//die;
////$sql = 'select * from tbl_content';
////
////$s = mysql_query($sql);
////$count = 0;
////while ($row = mysql_fetch_assoc($s)) {
////    $area = $row['area'];
////    $se_sql = "select * from tbl_areas where area='" . $area . "'";
////    $s_area = mysql_fetch_assoc(mysql_query($se_sql));
////    if ($s_area) {
////        $update_sql = "update tbl_content set area='" . $s_area['areaid'] . "' where id=" . $row['id'];
////        mysql_query($update_sql);
////        $count++;
////    }
////    echo ".";
////    flush();
////}
////echo $count;
////die;
//
//$url = "http://tianjin.anjuke.com/community/photos2/b/202275";
//$content = file_get_contents("compress.zlib://" . $url);
//
////$preg = "/<div\s+class=\"picture1\">.*?<img[^>]+src=\"(.+?)\"[^>]*>.*?<div\s+class=\"picture1\">/is";
//$preg = "/<img\s+[^>]*src=\"(http:\/\/pic1\.ajkimg\.com\/display\/.*?)\"\s+[^>]*>/is";
//preg_match($preg, $content, $martch);
//
//var_dump($martch);
//die;

