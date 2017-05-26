<?php

$weather_pm = array(
    "aqi" => 45,
    "area" => "北京",
    "co" => 0.65,
    "co_24h" => 0.728,
    "no2" => 20,
    "no2_24h" => 32,
    "o3" => 77,
    "o3_24h" => 79,
    "o3_8h" => 55,
    "o3_8h_24h" => 75,
    "pm10" => 44,
    "pm10_24h" => 24,
    "pm2_5" => 320, #100-150
    "pm2_5_24h" => 120,
    "quality" => "优",
    "level" => "一级",
    "so2" => 11,
    "so2_24h" => 14,
    "primary_pollutant" => "",
    "time_point" => "2014-03-05T16=>00=>00Z"
);
$weather_pm = '阴转晴';
$arr = json_encode($weather_pm);
print_r($arr);
die;
$s = array(
    array(
        'logoUrl' => 'http://image.ganji.com/mobile/softdown/softicon/2014-03-11/tuangou50@2x.png',
        'startVersion' => '5.0.1',
    ),
    array(
        'logoUrl' => 'http://image.ganji.com/mobile/softdown/softicon/2014-03-11/tuangou50@2x.png',
        'startVersion' => '5.0.4',
    ),
    array(
        'logoUrl' => 'http://image.ganji.com/mobile/softdown/softicon/2014-03-11/tuangou50@2x.png',
        'startVersion' => '5.0.6',
    ),
    array(
        'logoUrl' => 'http://image.ganji.com/mobile/softdown/softicon/2014-03-11/tuangou50@2x.png',
        'startVersion' => '5.0.2',
    ),
    array(
        'logoUrl' => 'http://image.ganji.com/mobile/softdown/softicon/2014-03-11/tuangou50@2x.png',
        'startVersion' => '5.0.3',
    ),
    array(
        'logoUrl' => 'http://image.ganji.com/mobile/softdown/softicon/2014-03-11/tuangou50@2x.png',
        'startVersion' => '5.0.9',
    ),
    array(
        'logoUrl' => 'http://image.ganji.com/mobile/softdown/softicon/2014-03-11/tuangou50@2x.png',
        'startVersion' => '5.0.5',
    ),
);
$r = array();
foreach ($s as $i => $l) {
    for ($j = $i + 1; $j <= count($s) - 2; $j++) {
//    for ($j = count($s) - 2; $j >= $i; $j--) {
        if ($s[$j]['startVersion'] < $s[$j - 1]['startVersion']) {
            $tmp = $s[$j];
            $s[$j] = $s[$j - 1];
            $s[$j - 1] = $tmp;
        }
    }
}

var_dump($s);
die;

//foreach ($s as $v) {
//    if (count($r) <= 0) {
//        $r[] = $v;
//    } else {
//        $last_k = count($r) - 1;
//        $last_r = $r[$last_k];
//        if ($last_r['startVersion'] >= $v['startVersion']) {
//            $r[] = $v;
//        } else {
//            $r[$last_k] = $v;
//            $r[$last_k + 1] = $last_r;
//        }
//    }
//}
//$s = array(
//    array(
//        'logoUrl' => 'http://image.ganji.com/mobile/softdown/softicon/2014-03-11/365rili50@2x.png',
//        'startVersion' => '5.0.0',
//    ),
//    array(
//        'logoUrl' => '1http://image.ganji.com/mobile/softdown/softicon/2014-03-11/tuangou50@2x.png',
//        'startVersion' => '5.0.1',
//    ),
//    array(
//        'logoUrl' => '2http://image.ganji.com/mobile/softdown/softicon/2014-03-11/tuangou50@2x.png',
//        'startVersion' => '5.0.2',
//    ),
////    array(
////        'logoUrl' => '3http://image.ganji.com/mobile/softdown/softicon/2014-03-11/tuangou50@2x.png',
////        'startVersion' => '5.0.3',
////    ),
//);

function sortLogInfoByVersion($loginfos) {
    $result = array();
    foreach ($loginfos as $k => $loginfo) {
        if (count($result) <= 0) {
            $result[] = $loginfo;
        } else {
            $tmp_result = $result;
            foreach ($tmp_result as $tmp_r) {
                if ($tmp_r['startVersion'] >= $loginfo['startVersion']) {
                    $result[] = $loginfo;
                } else {
                    
                }
            }
//            $last_k = count($result) - 1;
//            $last_r = $result[$last_k];
//
//            if ($last_r['startVersion'] >= $loginfo['startVersion']) {
//                $result[] = $loginfo;
//            } else {
//                $result[$last_k] = $loginfo;
//                $result[$last_k + 1] = $last_r;
//            }
//
//            if ($k == 2) {
//                var_dump($last_r);
//                var_dump($last_r['startVersion']);
//                var_dump($loginfo['startVersion']);
//                var_dump($last_r['startVersion'] >= $loginfo['startVersion']);die;
//            }
////            var_dump($result);
        }
    }
    return $result;
}

function array_sort($arr, $keys, $type = 'asc') {
    $keysvalue = $new_array = array();
    foreach ($arr as $k => $v) {
        $keysvalue[$k] = $v[$keys];
    }
    if ($type == 'asc') {
        asort($keysvalue);
    } else {
        arsort($keysvalue);
    }
    foreach ($keysvalue as $k => $v) {
        $new_array[$k] = $arr[$k];
    }
    return $new_array;
}

var_dump(array_sort($s, 'startVersion', 'desc'));
die;
