<?php

        $all_monkey_no = array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6);//总猴子数量
        $all_monkey_no = array_slice($all_monkey_no,-4,4,true);

var_dump( $all_monkey_no);
die;
//$sreq = urlencode("Hello World > how are you?");
//if (is_urlEncoded($sreq)) {
//    print "Was Encoded.\n";
//} else {
//    print "Not Encoded.\n";
//    print "Should be " . urlencode($sreq) . "\n";
//}
//风险帐号
$re = "发放失败，此请求可能存在风险，已被微信拦截";
if (strpos($re, '风险') !== false) {
    $update_win = array(
        'is_send' => 2, //风险帐号
    );
}
var_dump($update_win);
die;
?>
