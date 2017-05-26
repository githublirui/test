<?php
global $_W, $_GPC;
$modulePublic = '../addons/microb_shake/static/';
require_once MB_ROOT . '/source/Activity.class.php';
$user = $this->auth();

$id = $_GPC['actid'];
$id = intval($id);
$a = new Activity();
$activity = $a->getOne($id);
$prepare = $this->prepareActivity($activity, array('user' => $user));
if(is_error($prepare)) {
    $error = $prepare;
}
$footer_off = true;
$_W['page']['title'] = $activity['title'];

$_share = array();
$_share['title'] = $activity['share']['title'];
$_share['desc'] = $activity['share']['content'];
$_share['imgUrl'] = tomedia($activity['share']['image']);
$_share['link'] =  $_W['siteroot'].'app/' . substr($this->createMobileUrl('activity', array('actid'=>$activity['actid'])), 2);

$recents = $a->getRecents($id, 5);

if($activity['type'] == 'direct') {
    //直接发红包
    $gots = $a->getUserRecords($user['uid'], $id);
    $got = array();

    if(!empty($gots)) {
        foreach($gots as $g) {
            if($g['status'] == 'created') {
                $got = $g;
                break;
            }
        }
    }
    include $this->template('activity-direct');
}
