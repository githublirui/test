<?php
//session_start();
//$_SESSION['__:proxy:openid'] = 'oyIjYt9lQx9flMXl9F9NiAqrJd3g';
//debug
global $_W, $_GPC;

$modulePublic = '../addons/microb_shake/static/';
$foo = $_GPC['foo'];
$foos = array('list', 'create', 'modify', 'delete', 'devices', 'records');
$foo = in_array($foo, $foos) ? $foo : 'list';
require_once MB_ROOT . '/source/Activity.class.php';
require_once MB_ROOT . '/source/Api.class.php';

if($foo == 'create') {
    if($_W['ispost']) {
        $input = $_GPC;
        $input['rules'] = htmlspecialchars_decode($input['rules']);
        $input['start'] = strtotime($input['time']['start'] . ':00');
        $input['end'] = strtotime($input['time']['end'] . ':59');
        $input['share'] = serialize($input['share']);
        $input['shake'] = serialize($input['shake']);
        $input['limit'] = serialize($input['limit']);
        if($input['type'] == 'direct') {
            $input['tag'] = serialize($input['direct']);
        } else {
            $input['tag'] = serialize($input['tag']);
        }
        $gifts = array();
        foreach($input['gifts']['id'] as $k => $v) {
            $gifts[] = array(
                'gift'      => $v,
                'quantity'  => $input['gifts']['quantity'][$k],
                'rate'      => $input['gifts']['rate'][$k]
            );
        }

        $api = new Api();
        $page = unserialize($input['shake']);
        $page['url'] =  $_W['siteroot'].'app/' . substr($this->createMobileUrl('activity', array('actid'=>$ret)), 2);
        $page['title'] = $input['title'];
        $pageId = $api->createPage($page);
        if(is_error($pageId)) {
            message('创建活动失败, 错误详情: ' . $pageId['message']);
        }
        
        $a = new Activity();
        $ret = $a->create($input, $gifts);
        if(is_error($ret)) {
            message($ret['message']);
        } else {
            $page['url'] =  $_W['siteroot'].'app/' . substr($this->createMobileUrl('activity', array('actid'=>$ret)), 2);
            $api->modifyPage($pageId, $page);
            $a->touchPage($ret, $pageId);
            message("成功创建活动", $this->createWebUrl('activity'));
        }
    }
    $activity = array();
    $time = array();
    $time['start'] = date('Y-m-d 00:00');
    $time['end'] = date('Y-m-d 15:00');
    $activity['gifts'] = array();
    $activity['type'] = 'direct';

    load()->func('tpl');
    include $this->template('activity-form');
}

if($foo == 'modify') {
    $id = $_GPC['id'];
    $id = intval($id);
    $a = new Activity();
    $activity = $a->getOne($id);
    if(empty($activity)) {
        $this->error('访问错误');
    }
    if($_W['ispost']) {
        $input = $_GPC;
        $input['rules'] = htmlspecialchars_decode($input['rules']);
        $input['start'] = strtotime($input['time']['start'] . ':00');
        $input['end'] = strtotime($input['time']['end'] . ':59');
        $input['share'] = serialize($input['share']);
        $input['shake'] = serialize($input['shake']);
        $input['limit'] = serialize($input['limit']);
        if($input['type'] == 'direct') {
            $input['tag'] = serialize($input['direct']);
        } else {
            $input['tag'] = serialize($input['tag']);
        }
        $gifts = array();
        foreach($input['gifts']['id'] as $k => $v) {
            $gifts[] = array(
                'gift'      => $v,
                'quantity'  => $input['gifts']['quantity'][$k],
                'rate'      => $input['gifts']['rate'][$k]
            );
        }

        $page = unserialize($input['shake']);
        $page['title'] = $input['title'];
        if($page['title'] != $activity['title'] || $page['description'] != $activity['shake']['description'] || $page['image'] != $activity['shake']['image'] || $page['comment'] != $activity['shake']['comment']) {
            $page['url'] =  $_W['siteroot'].'app/' . substr($this->createMobileUrl('activity', array('actid'=>$activity['actid'])), 2);
            $api = new Api();
            $pageId = $api->modifyPage($activity['page'], $page);
            if(is_error($pageId)) {
                message($pageId['message']);
            }
        }
        
        $a = new Activity();
        $ret = $a->modify($id, $input, $gifts);
        if(is_error($ret)) {
            message($ret['message']);
        } else {
            
            message("成功编辑活动", $this->createWebUrl('activity'));
        }
    }
    $time = array();
    $time['start'] = date('Y-m-d H:i', $activity['start']);
    $time['end'] = date('Y-m-d H:i', $activity['end']);
    if($activity['type'] == 'direct') {
        $direct = $activity['tag'];
    }
    load()->func('tpl');
    include $this->template('activity-form');
}

if($foo == 'records') {
    $id = $_GPC['id'];
    $id = intval($id);
    $a = new Activity();
    $activity = $a->getOne($id);
    if(empty($activity)) {
        $this->error('访问错误');
    }
    $filters = array();
    $filters['activity'] = $id;
    $filters['nickname'] = $_GPC['nickname'];
    
    $pindex = intval($_GPC['page']);
    $pindex = max($pindex, 1);
    $psize = 15;
    $total = 0;

    $ds = $a->getAllRecords($filters, $pindex, $psize, $total);
    if(!empty($ds)) {
        $d = new Device();
        foreach($ds as &$row) {
            $row['device'] = $d->getOne($row['device']);
        }
        unset($row);
    }
    $pager = pagination($total, $pindex, $psize);
    
    include $this->template('activity-records');
}

if($foo == 'devices') {
    $id = $_GPC['id'];
    $id = intval($id);
    $a = new Activity();
    $activity = $a->getOne($id);
    if(empty($activity)) {
        $this->error('访问错误');
    }
    $d = new Device();
    if($_W['ispost']) {
        $api = new Api();
        $devices = $_GPC['device'];
        if($_GPC['type'] == 'bind') {
            foreach($devices as $did) {
                $device = $d->getOne($did);
                if(!empty($device)) {
                    $ret = $api->setDevicePages($device['device_id'], array(intval($activity['page'])));
                    $d->touchActivity($did, $id);
                }
            }
        }
        if($_GPC['type'] == 'unbind') {
            foreach($devices as $did) {
                $device = $d->getOne($did);
                if(!empty($device)) {
                    $ret = $api->setDevicePages($device['device_id'], array(intval($activity['page'])), false);
                    $d->touchActivity($did, 0);
                }
            }
            
        }
    }
    
    $filters = array();
    $filters['activity'] = $id;
    $binds = $d->getAll($filters);
    
    $filters = array();
    $filters['status'] = 'active';
    $filters['notactivity'] = $id;
    $unbinds = $d->getAll($filters);
    if(!empty($unbinds)) {
        foreach($unbinds as &$row) {
            $row['activity'] = $a->getOne($row['activity']);
        }
        unset($row);
    }

    $url = $this->createMobileUrl('activity', array('actid' => $activity['actid']));
    $activity['surl'] = $url;
    $url = substr($url, 2);
    $url = $_W['siteroot'] . 'app/' . $url;
    $activity['url'] = $url;
    include $this->template('activity-devices');
}

if($foo == 'delete') {
    $id = $_GPC['id'];
    $id = intval($id);
    $a = new Activity();
    $activity = $a->getOne($id);
    if(!empty($activity)) {
        $api = new Api();
        $api->deletePage($activity['page']);
    }
    $ret = $a->remove($id);
    if(is_error($ret)) {
        message($ret['message']);
    } else {
        message('操作成功', $this->createWebUrl('activity'));
    }
}

if($foo == 'list') {
    $a = new Activity();
    $ds = $a->getAll(array());
    if(is_array($ds)) {
        foreach($ds as &$row) {
            $row['count'] = $a->calcCount($row['actid']);
        }
        unset($row);
    }
    
    include $this->template('activity-list');
}