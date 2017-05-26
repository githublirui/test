<html>
    <head>
        <meta charset="utf-8"/>
    </head>
    <?php
#小区数据整理
//数据库配置
    $db_server = 'localhost';
    $db_user = 'root';
    $db_psw = '';
    $db_name = 'zgjw';
    $link = mysql_connect($db_server, $db_user, $db_psw) or die('connect error');
    mysql_select_db($db_name, $link) or die('select db error');
    mysql_query("SET NAMES UTF8");
    set_time_limit(0);

    $sql_province = "select * from tbl_content group by province";

    $r_province = mysql_query($sql_province);
	$i = 0;
	$j = 0;
    while ($row = mysql_fetch_assoc($r_province)) {
        if (!$row['province']) {
            continue;
        }
        $select_province = "select * from province where code=" . $row['province'];
        $re_pro = mysql_fetch_assoc(mysql_query($select_province));
        $content_num_province_sql = 'select count(*) as num from tbl_content where province=' . $row['province'];
        $content_num_province = mysql_fetch_assoc(mysql_query($content_num_province_sql));

        echo '<h3>省份: ' . $re_pro['name']. '</h3>';
        #查询市
        $selec_city = 'select * from tbl_content where province=' . $row['province'] . ' group by city';
        $r_city = mysql_query($selec_city);
        echo '<ul style="list-style:none;">';
		$c = 0;
        while ($row = mysql_fetch_assoc($r_city)) {
			$i++;
            $select_city = "select * from city where code=" . $row['city'];
            $re_city = mysql_fetch_assoc(mysql_query($select_city));
            $content_num_city_sql = 'select count(*) as num from tbl_content where city=' . $row['city'];
            $content_num_city = mysql_fetch_assoc(mysql_query($content_num_city_sql));
			$j = $j + ($content_num_city['num'] + ceil($content_num_city['num']*1/3));
			$c = $c + ($content_num_city['num'] + ceil($content_num_city['num']*1/3));
            echo "<li style='float:left;margin-left:15px;'>" . $re_city['name'] . " (" . 
				($content_num_city['num'] + ceil($content_num_city['num']*1/3))  . ")</li>";
        }
        echo "</ul>";
		echo '<div style="clear:both;"></div>';
		echo '<h3 style="margin-top: 5px;">总数' . $c . '</h3>';
    }
	echo "共有城市: ".$i." 小区数: ".$j;
    ?>
</html>