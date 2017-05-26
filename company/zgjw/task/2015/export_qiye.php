<?php

ini_set('memory_limit', -1);
set_time_limit(0);
/**
 * 
 * 中国家会员表excel 导出
 */
include LIB_PATH . '/PHPExcel.php';

$objPHPExcel = new PHPExcel();
$base = new BaseModel();
$zgjw_handle = $base->getDbHandler('lrtest');

$data_row_header[] = 'ID';
$data_row_header[] = '企业名称';
$data_row_header[] = '电话';
$data_row_header[] = '邮箱';
$data_row_header[] = '传真';
$data_row_header[] = '企业网址';
$data_row_header[] = '企业简介';
$data[] = $data_row_header;

$filename = "qiye.csv";
$file = dirname(__FILE__) . "/" . $filename;
$fp = fopen($file, 'a+');
fputcsv($fp, $data_row_header);
$line = 0;
$file_num = 1;
$id = 0;
while (true) {
    $page = 1;
    $page_num = 10000;
    $line_num = 150000; //每个文件多少行
    $offset = ($page - 1) * $page_num;
    $limit = $offset . ',' . $page_num;
    $rows = DBMysqlNamespace::fetch_all($zgjw_handle, 'jc_qiye', 'id>' . $id, 'id asc', $limit);
    if (!$rows) {
        break;
    }
    foreach ($rows as $row) {
        $data_row_data = array(
            $row['id'], $row['com_name'], $row['tel'], $row['email'], $row['fax'], $row['weburl'], strip_tags($row['content'])
        );
        fputcsv($fp, $data_row_data);
        $line++;
    }
    if ($line >= $line_num) {
        fclose($fp);
        $file_num++;
        $filename = "hy_" . $file_num . ".csv";
        $file = dirname(__FILE__) . "/" . $filename;
        $fp = fopen($file, 'a+');
        $line = 0;
        echo "$file_num\n";
        fputcsv($fp, $data_row_header);
    }
    echo ".";
    flush();
    $id = $row['id'];
    $page++;
    unset($row);
    unset($data_row_data);
}
fclose($fp);
?>
