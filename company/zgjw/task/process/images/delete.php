<?php

require_once '../../../lib/PHPExcel.php';

//���ݿ�����
$db_server = 'localhost';
$db_user = 'root';
$db_psw = '';
$db_name = 'zgjw';

$link = mysql_connect($db_server, $db_user, $db_psw) or die('connect error');
mysql_select_db($db_name, $link) or die('select db error');
mysql_query("SET NAMES UTF8");
set_time_limit(0);

$file_name = 'va.xlsx'; //����Ҫ�򿪵�Excel�ļ�����
#�ж��ļ��Ƿ����
if (!file_exists($file_name)) {
    exit($file_name . "�ļ������ڣ�");
}

$objPHPExcel = PHPExcel_IOFactory::load($file_name); //����Excel�ļ�
$lastRow = $objPHPExcel->getActiveSheet()->getHighestRow(); //�ж����м�¼����Ҫ�ѱ�ͷ���ȥ
//���õ�ǰ��sheet
$objPHPExcel->setActiveSheetIndex(0);
$sheet = $objPHPExcel->getActiveSheet();
$data_row = array();

for ($i = 2; $i < $lastRow + 1; $i++) {
	$file_name = $sheet->getCell('A' . $i)->getValue().'.jpg';
	#ɾ���ļ�
	unlink('anjuke/'.$file_name);
	#���ݿ�ɾ��
	$sql = "delete from tbl_house_type_manage where heme_general_img=".$file_name;
	echo '.';
	flush();
}
