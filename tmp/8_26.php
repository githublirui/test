<?php

$hy_total_price = 0.21;

//#���ȡ����
//$hy_pro_total_price_jiao = sprintf("%01.1f", $hy_pro_total_price);
//
//#����й��һ�ȡ��1%
//$to_zgjw_money = $hy_pro_total_price_jiao  * 0.01; #�����й���1%
//$hy_pro_total_price = $hy_pro_total_price - $to_zgjw_money; #�̻���ʵ�յ��ļ۸�
//
//var_dump($to_zgjw_money);
//var_dump($hy_pro_total_price);
//var_dump($hy_pro_total_price + $to_zgjw_money);
//die;


    $hy_total_price = sprintf("%01.2f", $hy_total_price);

	$to_zgjw_money = sprintf("%01.2f",($hy_total_price * 0.01)); #�����й���1%,ȡ����

	var_dump($to_zgjw_money);die;