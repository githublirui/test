<?php

ini_set('memory_limit', -1);
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
$data[] = $data_row_header;

$filename = "hy_1.csv";
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
    $rows = DBMysqlNamespace::fetch_all($zgjw_handle, 'hy', 'id>' . $id, 'id asc', $limit);
    if (!$rows) {
        break;
    }
    foreach ($rows as $row) {
        $data_row_data = array(
            $row['id'], get_hy_lx_txt($row['lx']), $row['usr'], $row['lxr'], $row['gsmc'], $row['gsdz'], $row['qq'], $row['sj'], $row['email'], $row['regrq'],
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
