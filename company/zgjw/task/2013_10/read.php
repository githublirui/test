<?php

/* * **************************
 *
 * 读出Excel文件的内容，并打印出来
 *
 * ************************** */
set_time_limit(0);
require_once 'PHPExcel.php'; //加载PHP处理Excel的类库
//ini_set("display_errors", 1);
//error_reporting(E_ALL);
$file_name = 'hy1_11_29.xlsx'; //设置要打开的Excel文件名字
#判断文件是否存在
if (!file_exists($file_name)) {
    exit($file_name . "文件不存在！");
}

include '../conn.php';
set_time_limit(0);

$objPHPExcel = PHPExcel_IOFactory::load($file_name); //加载Excel文件
$lastRow = $objPHPExcel->getActiveSheet()->getHighestRow(); //有多少行记录？不要把表头算进去
//设置当前的sheet
$objPHPExcel->setActiveSheetIndex(0);
$sheet = $objPHPExcel->getActiveSheet();
$data_row = array();
for ($i = 1; $i < $lastRow + 1; $i++) {
    $username = trim($sheet->getCell('A' . $i)->getValue());
    $sql = "select count(*) as num from hy where usr like '%" . $username . "%' or gsmc like '%" . $username . "%' or lxr like '%" . $username . "%'";
    var_dump($sql);die;
    $re = mysql_query($sql);
    $res = mysql_fetch_assoc($re);

    if ($res['num'] > 0) {
        $exists_hys[] = $username;
    } else {
        $not_exists_hys[] = $username;
    }
}
$exists_hys = array_unique($exists_hys);
$not_exists_hys = array_unique($not_exists_hys);
#写入excel
foreach ($exists_hys as $exists_hy) {
    $exists_hys_arr[] = array($exists_hy);
}
foreach ($not_exists_hys as $not_exists_hy) {
    $not_exists_hy_arr[] = array($not_exists_hy);
}

$objPHPExcel = new PHPExcel();
$objPHPExcel->getActiveSheet()->fromArray($not_exists_hy_arr, NULL, 'A1');
$filename = "hy1_not_exists_" . date('j_m_Y') . ".xlsx";
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save($filename);

$objPHPExcel = new PHPExcel();
$objPHPExcel->getActiveSheet()->fromArray($exists_hys_arr, NULL, 'A1');
$filename = "hy1_exists_" . date('j_m_Y') . ".xlsx";
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save($filename);
?>