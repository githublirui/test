<?php

class Device{
    public function create($entity){
        global $_W;
        $rec = array_elements(array('title', 'device_id','uuid','major','minor','audit_status','audit_comment','status'),$entity);
        $rec['uniacid'] = $_W['uniacid'];
        $ret = pdo_insert('mbsk_devices', $rec);
        if(!empty($ret)){
            return true;
        }else{
            return error(-1,'保存设备失败, 请稍后重试');
        }
    }
    
    public function modify($id, $entity){
        global $_W;
        $id = intval($id);
        $rec = array_elements(array('title','uuid','major','minor','audit_status','audit_comment','status'),$entity);
        $rec['uniacid'] = $_W['uniacid'];
        $condition = '`uniacid`=:uniacid';
        $pars = array();
        $pars[':uniacid'] = $rec['uniacid'];
        $sql = ' SELECT * FROM ' . tablename('mbsk_devices') . " WHERE {$condition}";
        pdo_fetch($sql, $pars);
        $ret = pdo_update('mbsk_devices', $rec, array('uniacid'=>$rec['uniacid'], 'id'=>$id));
        if($ret !== false){
            return true;
        }
    }


    public function touchActivity($device, $activity) {
        $filter = array();
        $filter['id'] = $device;
        
        $rec = array();
        $rec['activity'] = $activity;
        return pdo_update('mbsk_devices', $rec, $filter);
    }

    public function touchStatus($device, $status) {
        $filter = array();
        $filter['id'] = $device;

        $rec = array();
        $rec['status'] = $status;
        return pdo_update('mbsk_devices', $rec, $filter);
    }
    
    public function  remove($id){
        global $_W;
        $filter = array();
        $filter['uniacid'] = $_W['uniacid'];
        $filter['id'] = $id;
        pdo_delete('mbsk_devices', $filter);
        return true;
    }
    
    
    public function  getOne($id){
        global $_W;
        $pars = array();
        $pars[':uniacid'] = $_W['uniacid'];
        if(is_array($id)) {
            $condition = '`uniacid`=:uniacid AND `uuid`=:uuid AND `major`=:major AND `minor`=:minor';
            $pars[':uuid'] = $id['uuid'];
            $pars[':major'] = $id['major'];
            $pars[':minor'] = $id['minor'];
        } else {
            $condition = '`uniacid`=:uniacid AND `id`=:id';
            $pars[':id'] = $id;
        }
        
        $rec = pdo_fetch(" SELECT * FROM " . tablename('mbsk_devices') . " WHERE {$condition}", $pars);
        if(!empty($rec)){
            return $rec;
        }
        return array();
    }
    
    public function  getAll($filters, $pindex = 0, $psize = 20, &$total = 0){
        global $_W;
        $condition = '`uniacid`=:uniacid';
        $pars = array();
        $pars[':uniacid'] = $_W['uniacid'];
        if(!empty($filters['title'])){
            $condition .= ' AND `title` LIKE :title';
            $pars[':title'] = "%{$filters['title']}%";
        }
        if(!empty($filters['uuid'])){
            $condition .= ' AND `uuid` LIKE :uuid';
            $pars[':uuid'] = "%{$filters['uuid']}%";
        }
        if(!empty($filters['major'])){
            $condition .= ' AND `major` LIKE :major';
            $pars[':major'] = "%{$filters['major']}%";
        }
        if(!empty($filters['minor'])){
            $condition .= ' AND `minor` LIKE :minor';
            $pars[':minor'] = "%{$filters['minor']}%";
        }
        if(isset($filters['status'])){
            if($filters['status'] == 'active') {
                $condition .= ' AND `status`!=0';
            } else {
                $condition .= ' AND `status`=:status';
                $pars[':status'] = $filters['status'];
            }
        }
        if(!empty($filters['activity'])) {
            $condition .= ' AND `activity`=:activity';
            $pars[':activity'] = $filters['activity'];
        }
        if(!empty($filters['notactivity'])) {
            $condition .= ' AND `activity`!=:activity';
            $pars[':activity'] = $filters['notactivity'];
        }
        $sql = " SELECT * FROM " . tablename('mbsk_devices') . " WHERE {$condition} ORDER BY `id` DESC";
        if($pindex > 0){
            $sql = " SELECT COUNT(*) FROM " . tablename('mbsk_devices') . " WHERE {$condition}";
            $total = pdo_fetchcolumn($sql, $pars);
            $start = ($pindex - 1) * $psize;
            $sql = " SELECT * FROM " . tablename('mbsk_devices') . " WHERE {$condition} ORDER BY `id` DESC LIMIT {$start}, {$psize}";
        }
        $ds = pdo_fetchall($sql, $pars);
        if(!empty($ds)){
            return $ds;
        }
        return array();
    }
}