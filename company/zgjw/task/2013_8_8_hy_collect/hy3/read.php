<?php

/* * **************************
 *
 * 读出Excel文件的内容，并打印出来
 *
 * ************************** */
set_time_limit(0);

require_once 'config.inc.php';
require_once 'uc_client/client.php';
require_once 'function.php';
require_once 'PHPExcel.php'; //加载PHP处理Excel的类库
include('conn.php');
//ini_set("display_errors", 1);
//error_reporting(E_ALL);
$file_name = '2013_12_16.xlsx'; //设置要打开的Excel文件名字
#判断文件是否存在
if (!file_exists($file_name)) {
    exit($file_name . "文件不存在！");
}

$objPHPExcel = PHPExcel_IOFactory::load($file_name); //加载Excel文件
$lastRow = $objPHPExcel->getActiveSheet()->getHighestRow(); //有多少行记录？不要把表头算进去
//设置当前的sheet
$objPHPExcel->setActiveSheetIndex(0);
$sheet = $objPHPExcel->getActiveSheet();
$data_row = array();

for ($i = 2; $i < $lastRow + 1; $i++) {
    $data_row['username'] = trim($sheet->getCell('A' . $i)->getValue());
    $data_row['lxr'] = trim($sheet->getCell('B' . $i)->getValue());
    $data_row['gsmc'] = trim($sheet->getCell('C' . $i)->getValue());
    $data_row['hy1_id'] = trim($sheet->getCell('D' . $i)->getValue());
    $data_row['hy2_id'] = trim($sheet->getCell('E' . $i)->getValue());
    $data_row['email'] = trim($sheet->getCell('F' . $i)->getValue());
    $data_row['tel'] = trim($sheet->getCell('G' . $i)->getValue());

    $data_row['province'] = trim($sheet->getCell('H' . $i)->getValue());
    $data_row['city'] = trim($sheet->getCell('I' . $i)->getValue());
    $data_row['area'] = trim($sheet->getCell('J' . $i)->getValue());
    $data_row['district'] = trim($sheet->getCell('K' . $i)->getValue());

    $data_row['postal_code'] = trim($sheet->getCell('L' . $i)->getValue());
    $data_row['detail_address'] = trim($sheet->getCell('M' . $i)->getValue());
    $data_row['logo'] = trim($sheet->getCell('N' . $i)->getValue());
    $data_row['profl'] = trim($sheet->getCell('O' . $i)->getValue());

    $data_row = Utf8ToGbk($data_row);

    $data_row['province'] = getProvinceByName($data_row['province']);
    $data_row['city'] = getCityByName($data_row['city']);
    $data_row['area'] = getAreaByName($data_row['area']);
    $data_row['district'] = getDistrictByName($data_row['district']);
    $data_row['profl'] = getProflByName($data_row['profl']);
    if (!$data_row['username']) {
        continue;
    }
    insertData($data_row);
}
?>