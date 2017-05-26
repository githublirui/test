<?php

include 'config.inc.php';
include '../func.php';
include 'func.php';

#第一步: 采集安居客房型图
//$sql = "select * from tbl_content a
//LEFT JOIN `tbl_content_huxing_img` b on a.id=b.`tbl_content_id`
// where urltext !='' and (urltext like '%anjuke%') and b.id !='';";//不能为空的情况
//$sql = "select * from tbl_content where urltext !='' and (urltext like '%anjuke%') and id>74362  order by id asc";
//$r = mysql_query($sql);
//while ($row = mysql_fetch_assoc($r)) {
//    $url = $row['urltext'];
//    echo $url . "\n";
//    collectHuxingImageFromAnjuke($row);
//}
#第二步: 采集搜房网户型图
$sql = "select * from tbl_content where urltext !='' and (urltext like '%soufun%') order by id asc";
$r = mysql_query($sql);
while ($row = mysql_fetch_assoc($r)) {
    $url = $row['urltext'];
    echo $url . "\n";
    collectHuxingImageFromSouFun($row);
}
