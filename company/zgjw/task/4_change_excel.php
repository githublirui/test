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

        echo '<h3>省份: ' . $re_pro['name'] . '</h3>';
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
            $j = $j + ($content_num_city['num'] + ceil($content_num_city['num'] * 1 / 3));
            $c = $c + ($content_num_city['num'] + ceil($content_num_city['num'] * 1 / 3));
            echo "<li style='float:left;margin-left:15px;'>" . $re_city['name'] . " (" .
            ($content_num_city['num'] + ceil($content_num_city['num'] * 1 / 3)) . ")</li>";
        }
        echo "</ul>";
        echo '<div style="clear:both;"></div>';
        echo '<h3 style="margin-top: 5px;">总数' . $c . '</h3>';
    }
    echo "共有城市: " . $i . " 小区数: " . $j;
    ?>
</html>


<?php
header('Content-Type:application/msexcel');
$objPHPExcel = new PHPExcel();

// set default font
$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
$objPHPExcel->getDefaultStyle()->getFont()->setSize(9);
$objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);


// data - header
$data_row = array();
//$data_row_header = array();
//
//$data_row_header[] = '姓名';
//$data_row_header[] = '邮箱';

//$data_row[0] = $data_row_header;

// data - body                    

foreach ($valid_emails as $valid_email) {
    $data_row_data = array();
    $data_row_data[] = $valid_email[0];
    $data_row_data[] = $valid_email[1];

    $data_row[] = $data_row_data;
}
//set height
for ($i = 1; $i < count($data_row) + 1; $i++) {
    $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(12.00);
}

//set up style array
$title_style_arr = array(
    'font' => array(
        'bold' => true,
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
        'wrap' => true,
    ),
);

$record = array(); //record rows and columns of the style
// format the array three-dimensional array to a two-dimensional array
foreach ($data_row as $k => $_data_row) {
    foreach ($_data_row as $_k => $d) {
        if (is_array($d)) {
            list($class, $data) = $d;
            $data_row[$k][$_k] = $data;
            $record[] = array($k, $_k, $d[0]);
        } else {
            $data_row[$k][$_k] = $d;
        }
    }
}
//$header_column = array(); //record specified column
////set header style ,auto width
//for ($i = 1; $i < count($data_row[0]) + 1; $i++) {
//    $objPHPExcel->getActiveSheet()->getStyle((Utils::numToExcelAlpha($i)) . "1")->applyFromArray($title_style_arr);
//    $objPHPExcel->getActiveSheet()->getColumnDimension(Utils::numToExcelAlpha($i))->setAutoSize(true);
//    foreach ($data_row[0] as $k => $h) {
//        $header_column[$h] = $k;
//    }
//}

// fills data
$objPHPExcel->getActiveSheet()->fromArray($data_row, NULL, 'A1');


// Save Excel 2007 file
$filename = "Email_" . date('j_m_Y') . ".xlsx";
ob_end_clean();
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
?>