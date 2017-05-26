<?php

require_once '../../../lib/PHPExcel.php';

//数据库配置
$db_server = 'localhost';
$db_user = 'root';
$db_psw = '';
$db_name = 'zgjw';

$link = mysql_connect($db_server, $db_user, $db_psw) or die('connect error');
mysql_select_db($db_name, $link) or die('select db error');
mysql_query("SET NAMES UTF8");
set_time_limit(0);

$file_name = 'va.xlsx'; //设置要打开的Excel文件名字
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
	$file_name = $sheet->getCell('A' . $i)->getValue().'.jpg';
	#删除文件
	unlink('anjuke/'.$file_name);
	#数据库删除
	$sql = "delete from tbl_house_type_manage where heme_general_img=".$file_name;
	echo '.';
	flush();
}
