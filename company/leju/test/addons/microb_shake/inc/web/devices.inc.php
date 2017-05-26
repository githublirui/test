<?php
global $_W, $_GPC;

$foo = $_GPC['foo'];
$foos = array('list', 'create', 'modify', 'delete', 'refresh', 'download');
$foo = in_array($foo, $foos) ? $foo : 'list';
require_once MB_ROOT . '/source/Device.class.php';
require_once MB_ROOT . '/source/Api.class.php';
if($foo == 'refresh'){
    $id = $_GPC['device'];
    $d = new Device();
    $device = $d->getOne($id);
    if(!empty($device)) {
        $api = new Api();
        $ret = $api->queryDevice($device['device_id']);
        if(!is_error($ret)) {
            $d->touchStatus($id, $ret['status']);
            exit('success');
        } else {
            exit('设备无效或未激活');
        }
    }
    exit('访问错误请稍后重试');
}

if($foo == 'create') {
    if($_W['ispost']){
        $a = new Api();
        $title = $_GPC['title'];
        $re = $a->createDevice($title);
        if(is_error($re)) {
            message('创建设备失败, 详细错误信息为: ' . $re['message']);
        } else {
            $entity = array_elements(array('device_id','uuid','major','minor'), $re);
            $entity['title'] = $title;
            $c = new Device();
            $ret = $c->create($entity);
            if(is_error($ret)){
                message($ret['message']);
            } else {
                message('成功创建设备', $this->createWebUrl('devices'));
            }
        }
    }
    include $this->template('device-from');
}

if($foo == 'download') {
    if($_W['ispost']){
        $api = new Api();
        $device = $_GPC['device_id'];
        $entity = $api->queryDevice($device);
        if(!is_error($entity)) {
            $r = array_elements(array('device_id', 'major', 'minor', 'status', 'uuid'), $entity);
            $r['title'] = $entity['comment'];
            $c = new Device();
            $ret = $c->create($r);
            if(is_error($ret)){
                message($ret['message']);
            } else {
                message('成功同步设备', $this->createWebUrl('devices'));
            }
        } else {
            exit('设备无效或未激活');
        }
    }
    include $this->template('device-download');
}

if($foo == 'modify') {
    $id = $_GPC['id'];
    $g = new Device();
    $entity = $g -> getOne($id);
    if(empty($entity)){
        message('访问错误');
    }
    if($_W['ispost']){
        $a = new Api();
        $title = $_GPC['title'];
        $re = $a->setDeviceTitle($entity['device_id'], $title);
        if(is_error($re)){
            message($re['message']);
        } else {
            $c = new Device();
            $entity['title'] = $title;
            $ret = $c->modify($id, $entity);
            if(is_error($ret)){
                message($ret['message']);
            }else{
                message('成功修改设备信息', $this->createWebUrl('devices'));
            }    
        }
        
    }
    include $this->template('device-from');
}

if($foo == 'delete'){
    $id = $_GPC['id'];
    $c = new Device();
    $c->remove($id);
    message('成功删除设备信息', $this->createWebUrl('devices',array('id' => $id)));
}

if($foo == 'list') {
    $c = new Device();
    $filters = array();
    $filters['title'] = $_GPC['title'];
    $filters['uuid'] = $_GPC['uuid'];
    $filters['major'] = $_GPC['major'];
    $filters['minor'] = $_GPC['minor'];
    $filters['audit_status'] = $_GPC['audit_status'];
    $filters['audit_comment'] = $_GPC['audit_comment'];
    $filters['status'] = $_GPC['status'];
    $pindex = intval($_GPC['page']);
    $pindex = max($pindex, 1);
    $psize = 5;
    $total = 0;
    
    $ds = $c->getAll($filters, $pindex, $psize, $total);
    if(!empty($ds)) {
        $a = new Activity();
        foreach($ds as &$row) {
            $row['activity'] = $a->getOne($row['activity']);
        }
        unset($row);
    }
    $pager = pagination($total, $pindex, $psize);
    
    include $this->template('device-list');
}