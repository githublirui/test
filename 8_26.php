<?php

$hy_total_price = 0.21;

//#金额取到角
//$hy_pro_total_price_jiao = sprintf("%01.1f", $hy_pro_total_price);
//
//#算出中国家获取的1%
//$to_zgjw_money = $hy_pro_total_price_jiao  * 0.01; #返给中国家1%
//$hy_pro_total_price = $hy_pro_total_price - $to_zgjw_money; #商户真实收到的价格
//
//var_dump($to_zgjw_money);
//var_dump($hy_pro_total_price);
//var_dump($hy_pro_total_price + $to_zgjw_money);
//die;


    $hy_total_price = sprintf("%01.2f", $hy_total_price);

	$to_zgjw_money = sprintf("%01.2f",($hy_total_price * 0.01)); #返给中国家1%,取整数

	var_dump($to_zgjw_money);die;