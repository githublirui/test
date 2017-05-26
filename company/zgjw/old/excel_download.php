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
$data_row_header = array();

$data_row_header[] = '姓名';
$data_row_header[] = '邮箱';

$data_row[0] = $data_row_header;

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
$header_column = array(); //record specified column
//set header style ,auto width
for ($i = 1; $i < count($data_row[0]) + 1; $i++) {
    $objPHPExcel->getActiveSheet()->getStyle((Utils::numToExcelAlpha($i)) . "1")->applyFromArray($title_style_arr);
    $objPHPExcel->getActiveSheet()->getColumnDimension(Utils::numToExcelAlpha($i))->setAutoSize(true);
    foreach ($data_row[0] as $k => $h) {
        $header_column[$h] = $k;
    }
}

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