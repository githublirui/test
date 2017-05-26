<?php
include "../config.inc.php";
include "../../lib/db/MyPDO.class.php";
$db = MyPDO::getInstance();
$_POST['areas'] = trim($_POST['areas']);
$area_names = explode("\n", $_POST['areas']);
$not_founds = array();
$tbl_content_citys = array();
foreach ($area_names as $area_name) {
    $area_name = trim($area_name);
    $sql = "
        select t.id,
       t.name,
       c.name as city_name,
       t.area,
       t.urltext
from tbl_content t
     left join area c on c.`code` = t.`area`
where t.name = '" . $area_name . "' and t.province=120000;
";
    
    $tbl_content = $db->fetchOne($sql);
    if (!$tbl_content) {
        $not_founds[] = $area_name;
        $tbl_content_citys[] = array('name' => '&nbsp', 'url' => '&nbsp');
    } else {
        $tbl_content_citys[] = array('name' => $tbl_content['city_name'], 'url' => $tbl_content['urltext']);
    }
}
$not_founds = array_unique($not_founds);
?>

<!doctype html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="content-type" content="text/html;charset=UTF-8">
    <title>小区EXCEL查询</title>
</head>
<body>
    <?php if (count($tbl_content_citys) > 0) { ?>
        <table>
            <tr>
                <th>所属区</th>
            </tr>
            <?php foreach ($tbl_content_citys as $tbl_content_city) {
                ?>
                <tr>
                    <td><?php echo $tbl_content_city['name'] ?></td>
                </tr>
                <?php
            }
            ?> 
        </table>
        <table>
            <tr>
                <th>链接地址</th>
            </tr>
            <?php foreach ($tbl_content_citys as $tbl_content_city) {
                ?>
                <tr>
                    <td><?php echo $tbl_content_city['url'] ?></td>
                </tr>
                <?php
            }
            ?> 
        </table>
        <table>
            <tr>
                <th>未找到的小区</th>
            </tr>
            <?php foreach ($not_founds as $not_found) {
                ?>
                <tr>
                    <td><?php echo $not_found ?></td>
                </tr>
                <?php
            }
            ?> 
        </table>
        <?php
    }
    ?>
    <form method="POST" >
        <textarea name="areas" style="height:500px; width:400px;"></textarea>
        <input type="submit" value="查询" />
    </form>
</body>
</html>
