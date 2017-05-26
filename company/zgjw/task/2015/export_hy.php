<?php

set_time_limit(0);
/**
 * 
 * 中国家会员表excel 导出
 */
include LIB_PATH . '/PHPExcel.php';

function get_hy_lx_txt($lx) {
    $result = '';
    switch ($lx) {
        case 1:
            $result = "品牌厂商";
            break;
        case 2:
            $result = "家居商城";
            break;
        case 3:
            $result = "实体商铺";
            break;
        case 4:
            $result = "装饰公司";
            break;
        case 5:
            $result = "设计师";
            break;
        case 6:
            $result = "工长";
            break;
        case 7:
            $result = "装修业主";
            break;
        case 8:
            $result = "房产商";
            break;
        case 9:
            $result = "大学生";
            break;
        case 10:
            $result = "风水师";
            break;
        case 11:
            $result = "设计工作室";
            break;
        default:
            return false;
            break;
    }
    return $result;
}

$objPHPExcel = new PHPExcel();
$base = new BaseModel();
$zgjw_handle = $base->getDbHandler('zgjw');

$id = 0;
$rows = DBMysqlNamespace::fetch_all($zgjw_handle, 'hy', 'id>' . $id, 'id desc');
if (!$rows) {
    break;
}

// set default font
$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
$objPHPExcel->getDefaultStyle()->getFont()->setSize(9);
$objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);


// data - header
$data_row = array();
$data_row_header = array();

$data_row_header[] = '会员ID';
$data_row_header[] = '会员类型';
$data_row_header[] = '用户名';
$data_row_header[] = '姓名';
$data_row_header[] = '公司名称';
$data_row_header[] = '公司地址';
$data_row_header[] = 'QQ';
$data_row_header[] = '手机号';
$data_row_header[] = '邮箱';
$data_row_header[] = '注册时间';
$data_row[0] = $data_row_header;
// data - body                    
foreach ($rows as $row) {
    $data_row_data = array();
    $data_row_data[] = $row['id'];
    $data_row_data[] = get_hy_lx_txt($row['lx']);
    $data_row_data[] = $row['usr'];
    $data_row_data[] = $row['lxr'];
    $data_row_data[] = $row['gsmc'];
    $data_row_data[] = $row['gsdz'];
    $data_row_data[] = $row['qq'];
    $data_row_data[] = $row['sj'];
    $data_row_data[] = $row['email'];
    $data_row_data[] = $row['regrq'];
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
$filename = "hy_" . time() . ".xlsx";
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->setPreCalculateFormulas(false);
$objWriter->save(dirname(__FILE__) . "/" . $filename);
exit;
?>
