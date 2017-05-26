<?php
require './../../../framework/bootstrap.inc.php';

$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];


if (!empty($postStr)){
    $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
    $out_trade_no = $postObj->out_trade_no;
    $transaction_id = $postObj->transaction_id;
    $total_fee = $postObj->total_fee;

    $ret=explode('_',$out_trade_no);
    $uniacid=$ret[1];
    $uid=$ret[2];

	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('teabox_penny_pay')." WHERE uniacid=".$uniacid);

    $data=array(
        'uid'=>$uid,
        'uniacid'=>$uniacid,
        'out_trade_no'=>$out_trade_no,
        'transaction_id'=>$transaction_id,
        'total_fee'=>$total_fee/100,
        'dateline'=>TIMESTAMP,
		'sort'=>$total+1
    );

    pdo_insert('teabox_penny_pay', $data);
}
echo "success";