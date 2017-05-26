<?php

//$left_amount = 10;
//$min = 1;
//$max = 0;
//while ($left_amount > 0) {
//    if ($left_amount > 0) {
////            $fa_amount = rand($_POST['min'] * 100, $_POST['max'] * 100);
////            $fa_amount = ($fa_amount / 100);
//        $fa_amount = rand($min, $max);
//        if ($fa_amount > $left_amount) {
//            $fa_amount = $left_amount;
//        }
//    }
//    $left_amount -= $fa_amount;
//    echo $fa_amount . "<br/>";
//}
//die;

/**
 * 随机手机号
 * @return type
 */
function rand_phone() {
    $phoneArr = array();     //保存手机号数组
    $num = 1;                 //生成手机号的个数
    $twoArr = array(3, 5, 8);  //手机号的第二位
    $tArr = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);    //手机号第二位为3时，手机号的第3位数据集，以及手机号的第4位到第11位
    $ntArr = array(0, 1, 2, 3, 5, 6, 7, 8, 9);      //手机号第二位不为3时，手机号的第3位数据集
    for ($i = 0; $i < $num; $i++) {
        $phone[0] = 1;                      //手机号第一位必须为1
        for ($j = 1; $j < 11; $j++) {
            if ($j == 1) {
                $t = rand(0, 2);          //随机生成手机号的第二位数字
                $phone[$j] = $twoArr[$t];
            } elseif ($j == 2 && $phone[1] != 3) {     //当第二位不为3时，随机生成其余手机号
                $th = rand(0, 8);
                $phone[$j] = $ntArr[$th];
            } else {                                         //当第二位为3时，随机生成其余手机号
                $th = rand(0, 9);
                $phone[$j] = $tArr[$th];
            }
        }
        $phoneArr[] = implode("", $phone);          //将手机号数组合并成字符串
    }
    return $phoneArr[0];
}

var_dump(rand_phone());
die;
echo 123131;
die;
sleep(5);
echo 555;
die;
define('URL', 'http://test.local/a.php');
set_time_limit(500);
$url = URL;
echo $url . '<br />';
$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_URL, $url);
//curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
//curl_setopt($ch, CURLOPT_POST, 1);
//curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
curl_setopt($ch, CURLOPT_VERBOSE, 0);
// curl_setopt($ch, CURLOPT_NOBODY, true);
$result = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);
var_dump($result);
die;
