<?php
	header("Content-type:text/html;charset=utf-8");
	
//    ini_set('display_errors', 'On');

	$fp = fopen("city.txt", "r"); 
	while(! feof($fp)) 
	{ 
	$addr = fgets($fp); 
	} 
	fclose($fp); 

    require_once "./communication.func.php";
    require_once "./db.class.php";
    $db = new mysqldb($hongbao_db);

    $sql =  "select * from ims_mbrp_cron where left_amount > 0 order by mbrp_cron_id limit 1";
    $res = mysql_query($sql);
    $_arr = array();
    $row = mysql_fetch_array($res, MYSQL_ASSOC);

    if(empty($row)) {
        out_put('无需要发放的记录');die;
    }
    $left_amount = $row['left_amount'];
    $addr = explode('|', $addr);
    //每次发放100个红包
    for($i=0;$i<1000;$i++) {
        if($left_amount <= 0) {
            out_put('本次已经发放完成mid');die;
        }
		
        $fa_amount = rand($row['min']*100, $row['max']*100);
		$fa_amount = ($fa_amount/100);
	
        if($fa_amount > $left_amount) {
            $fa_amount = $left_amount;
        }
        $left_amount -= $fa_amount;
        $uniacid = '|'.$row['uniacid'];
		
        $sql =  "select * from jilu where hongbao_use = 0 OR hongbao_huodong not like '%".$uniacid."%' limit 1";
        $res = mysql_query($sql);
        $_arr = array();
        $jilu_row = mysql_fetch_array($res, MYSQL_ASSOC);
		
        if(empty($jilu_row)) {
			
            out_put('没获取到粉丝记录，发放失败');
        }

        $num = array_rand($addr,1);
        $_addr = explode(',', $addr[$num]);
        $fans_arr  = array(
            'uniacid' => $row['uniacid'],
            'openid'=> $jilu_row['openid'].rand(1,9),
            'nickname'=>$jilu_row['name'],
			'gender' =>rand(1,2),
			'country' =>'中国',
            'state'=> $_addr[0] ? $_addr[0] : '',
            'city'=> $_addr[1] ? $_addr[1] : '',
            'avatar'=> $jilu_row['headimgurl']
        );
        $db->row_insert('ims_mbrp_fans', $fans_arr);
        $uid = mysql_insert_id();
        $record_arr = array(
            'uniacid' => $row['uniacid'],
            'activity' => $row['activity'],
            'uid' => $uid,
            'fee' => $fa_amount,
            'gift' => 1,
            'created' => time()+rand(1,100),
            'completed' => time()+rand(100,1000),
            'status' => 'complete',
        );
        $db->row_insert('ims_mbrp_records', $record_arr);
		$record_id = mysql_insert_id();
		$gift_arr = array(
            'uniacid' => $row['uniacid'],
            'type' => 'cash',
            'title' => '111'
        );
        $db->row_insert('ims_mbrp_gifts', $gift_arr);
        

        $fee = $fa_amount*100;

        $status = true;
		
        if($status === true) {

            $sql = "update ims_mbrp_cron set left_amount = left_amount - ".$fa_amount." where mbrp_cron_id = ".$row['mbrp_cron_id'] ;
            mysql_query($sql);

            if(empty($jilu_row['hongbao_huodong'])) {
                $hongbao_huodong  = '|'.$row['uniacid'].'|';
            } else {
                $hongbao_huodong = $jilu_row['hongbao_huodong'].'|'.$row['uniacid'];
            }
            $sql2 = "update jilu set hongbao_use = 1,hongbao_huodong ='{$hongbao_huodong}' where id = ".$jilu_row['id'] ;

            mysql_query($sql2);
        } else {
            $sql3 = "update ims_mbrp_records set status = '{$status}' where id = ".$record_id ;
            mysql_query($sql3);
            out_put('打款失败，失败信息'.$status);
        }
    }
    out_put('本次已经发放完成end');

function out_put($msg) {
    $msg = date('Y-m-d H:i:s').$msg;
    exit($msg);
}
//$res = send_fee('o5YC3t6prx_1YWhaCypbCOQsegDM', 100, $record_id);
