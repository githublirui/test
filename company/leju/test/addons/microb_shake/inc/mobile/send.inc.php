<?php
global $_W, $_GPC;
require_once MB_ROOT . '/source/Activity.class.php';
$user = $this->auth();

$id = $_GPC['actid'];
$id = intval($id);
$a = new Activity();
$activity = $a->getOne($id);
$prepare = $this->prepareActivity($activity, array('user' => $user));
if(is_error($prepare)) {
    $error = $prepare;
    exit($error['message']);
}

$api = new Api();
$current = $api->getCurrent();
$info = array();
$info['distance'] = $current['distance'];
$info['device'] = $current['device']['id'];

if($activity['type'] == 'direct') {
    $rid = intval($_GPC['rid']);
    if(!empty($rid)) {
        $record = $a->getOneRecord($rid);
        if(empty($record) || $record['activity'] != $activity['actid'] || $record['uid'] != $user['uid']) {
            exit('非法的访问');
        }
        $ret = $record;
    } else {
        $ret = $a->grap($user, $activity, $info);
    }
    if(is_error($ret)) {
        exit($ret['message']);
    } elseif ($ret['status'] == 'none') {
        exit('none');
    } else {
        $ret = $this->send($activity, $ret, $user);
        if(is_error($ret)) {
            exit('礼品发放失败, 你可以在活动结束之前重新领取. 活动结束后无法领取, 请注意');
            exit($ret['message']);
        }
        exit('success');
    }
}
exit('没有领取到红包');
