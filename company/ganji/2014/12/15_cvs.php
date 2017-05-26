<?php

$redis = new Redis('123.56.45.181', '6379');

$s = $redis->get('key1');
var_dump($s);
die;
//$sum = 15;
//$min = 1;
//$max = 6;
//$num = 3;
//function getRand($num, $count) {
//    $countArr = array();
//    for ($i = 1; $i <= $count; $i++) {
//        $countArr[$i] = mt_rand(1, 6);
//    }
//    if (array_sum($countArr) != $num) {
//        return getRand($num, $count);
//    } else {
//        return $countArr;
//    }
//}
//
//$t = array(0, 0);
//
//function recursive($res = array(), $tmp = array(), $type = 0) {
//    $num = 2;
//    $min = 1;
//    $max = 5;
//    if (!empty($tmp)) {
//        $res[] = $tmp;
//        if ($tmp[$type] >= $max) {
//            $tmp[$type + 1] ++;
//        } else {
//            $tmp[$type] ++;
//        }
//        var_dump($type);
//        die;
//    } else {
//        $tmp = array_fill(0, $num, $min);
//        $type = $num;
//    }
//    recursive($res, $tmp, $type);
//
//    //退出
//    $returnFlag = true;
//    for ($i = 0; $i < $tmp; $i++) {
//        if (!isset($tmp[$i]) || $tmp[$i] < $max) { //循环到最大，退出
//            $returnFlag = false;
//        }
//    }
//    if ($returnFlag) {
//        return $res;
//    }
//}
//$x + $y + $z = 15;
//function recursive($loop = 2, $loopArr) {
//
//    for ($i = 1; $i <= 5; $i++) {
//        for ($j = 1; $j <= 5; $j++) {
//            echo $i . '-' . $j . '<br/>';
//        }
//    }
//}
//function recursive($res, $sum = 0) {
//    if ($res == $sum) {
//        return $res;
//    } else {
//        $sum++;
//        return recursive($res, $sum);
//    }
//}

/**
 * 
 * @param type $min  最小数
 * @param type $max  最大数
 * @param type $num  相加的个数
 * @param type $res  保存结果
 * @param type $sum  保存循环的和
 * @param type $type key
 * @return type
 */
//function recursive($sum = 15, $min = 1, $max = 6, $num = 3, $res = array(), $tmp = array(), $type = 0) {
//    $arrSum = array_sum($tmp); //和
//    $reMin = $sum - $max * ($num - 1); //最小数，优化，减少循环次数
//    if ($arrSum == $res) { //符合条件，添加到数组
//        $res[] = $sum;
//    }
//
//    //循环
//    if (empty($tmp)) {
//        $tmp = array_fill($reMin, $num, $reMin);
//        $type = $num;
//    } else {
//        //最后一个循环
//        if ($tmp[$type] >= $max) {
//            $tmp[$type--] + 1;
//            $tmp = array_fill($reMin, $num, $reMin);
//        }
//
//        $tmp[$type] ++;
//    }
//
//    recursive($min, $num, $max, $res, $tmp, 0);
//
//    //退出
//    $returnFlag = true;
//    for ($i = 0; $i < $tmp; $i++) {
//        if (!isset($tmp[$i]) || $tmp[$i] < $max) { //循环到最大，退出
//            $returnFlag = false;
//        }
//    }
//    if ($returnFlag) {
//        return $res;
//    }
//}

/**
 * 
 * @param type $sum  和
 * @param type $min  最小值
 * @param type $max  最大值
 * @param type $num  几个数
 */
function testadd($sum = 15, $min = 1, $max = 6, $num = 3) {
    $result = array();
    $forStart = '';
    $forEnd = '';
    $forMiddle = '';
    $reMin = $min; //最小数，优化，减少循环次数

    for ($m = 0; $m < $num - 1; $m++) {
        $forStart .= sprintf(' for ($i%s = ' . $reMin . '; $i%s <= 6; $i%s++) { ', $m, $m, $m);
        $forEnd .= ' } ';
    }
    $forMiddle = '
         $tmpValue = array();
    $tmps = array_map(function ($v) {
        return \'$i\' . $v;
    }, range(0, $num-2));';
    $forMiddle .= '
   foreach ($tmps as $k => $tmp) {
        $tmpValue[$k] = eval("return ".$tmp.";");
   }
 ';
    $forMiddle .= '
        $left = $sum - array_sum($tmpValue);
if ($left <= $max && $left >= $min) {
        $tmpValue[] = $left;
        $result[] = $tmpValue;
    }';
    $evalResult = $forStart . $forMiddle . $forEnd;
    eval($evalResult);
    return $result;
}

var_dump(testadd(10, 1, 6, 3));
die;
//$forStart = 'for ($i%s = 3; $i%s <= 6; $i%s++) {';
//
//
//$forEnd = '}';
//for ($i = 3; $i <= 6; $i++) {
//    for ($j = 3; $j <= 6; $j++) {
//        if (15 - ($i + $j) <= 6) {
//            file_put_contents(dirname(__FILE__) . '/tmp.txt', $i . '+' . $j . '+' . (15 - ($i + $j)) . "\n", FILE_APPEND);
//        }
//    }
//}
//function testAdd($sum = 15, $num = 3, $min = 1, $max = 6, $tmpArr = array(), $result = array()) {
//    $reMin = $sum - $max * ($num - 1);
//    for ($i = $reMin; $i <= $max; $i++) {
//        if (count($tmpArr) == $num) {
//            if (array_sum($tmpArr) == $sum) {
//            
//                $result[] = $tmpArr;
//            } else {
//                testAdd($sum, $num, $min, $max, array(), $result);
//            }
//        } else {
//            $tmpArr[] = $i;
//            var_dump($tmpArr);
//            die;
//            testAdd($sum, $num, $min, $max, $tmpArr, $result);
//        }
//    }
//    return $result;
//}

var_dump(testAdd());
die;
ini_set('display_errors', 1);
error_reporting(E_ALL);
$b = 'sdfasdf123';
$a = $b['name'];
var_dump($a);
die;
//csv下载 
//$fp = fopen($file, 'w');
//foreach ($data as $line) {
//    fputcsv($fp, $line);
//}
//
//fclose($fp);
//
//$csv_data = file_get_contents($file);
//$csv_data = str_replace("\n", "\r\n", $csv_data);
//file_put_contents($file, $csv_data);

$filename = dirname(__FILE__) . '/Book1.csv';
header('Content-Disposition: attachment; filename=book1.csv');
header("Content-Type: application/doc");
header('Content-Length: ' . filesize($filename));
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');
readfile($filename);
?>
