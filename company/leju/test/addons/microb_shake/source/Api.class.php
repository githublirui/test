<?php

class Api {
    
    private function getAccessToken() {
        load()->func('communication');
        load()->classs('weixin.account');
        $accObj= new WeixinAccount();
        $access_token = $accObj->fetch_available_token();
        return $access_token;
    }

    /**
     * 创建一个设备
     * 返回设备信息, 参照表结构
     * @return row|error
     */
    public function createDevice($title) {
        $token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/shakearound/device/applyid?access_token={$token}";
        $pars = array();
        $pars['quantity'] = 1;
        $pars['apply_reason'] = $title . '需要申请设备';
        $pars['comment'] = $title;
        $resp = ihttp_post($url, json_encode($pars));
        if(is_error($resp)) {
            return $resp;
        }
        $ret = @json_decode($resp['content'], true);
        if(is_array($ret) && !empty($ret['data'])) {
            $r = $ret['data']['device_identifiers'][0];
            return $r;
        }
        return error(-1, $resp['content']);
    }

    /**
     * 设置一个设备的备注信息
     * @param $identifier string|array
     * @return true|error
     */
    public function setDeviceTitle($identifier, $title) {
        $token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/shakearound/device/update?access_token={$token}";   
        $pars = array();
        if(is_string($identifier)) {
            $pars['device_identifier']['device_id'] = intval($identifier);
        } else {
            $pars['device_identifier']['device_id'] = intval($identifier['device_id']);
            $pars['device_identifier']['uuid'] = $identifier['uuid'];
            $pars['device_identifier']['major'] = intval($identifier['major']);
            $pars['device_identifier']['minor'] = intval($identifier['minor']);
        }
        $pars['comment'] = $title;
        $resp = ihttp_post($url, json_encode($pars));
        if(is_error($resp)){
            return $resp;    
        }
        $ret = @json_decode($resp['content'],true);
        if(is_array($ret) && $ret['errcode'] == '0'){
            return true;    
        }
        return error(-1,$resp['content']);
    }

    /**
     * @param $identifier   string|array
     * @param $pages        array
     * @return true|error
     */
    public function setDevicePages($identifier, $pages, $bind = true) {
        $token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/shakearound/device/bindpage?access_token={$token}";   
        $pars = array();
        if(is_string($identifier)){
            $pars['device_identifier']['device_id'] = intval($identifier);    
        } else {
            $pars['device_identifier']['device_id'] = intval($identifier['device_id']);
            $pars['device_identifier']['major'] = $identifier['major'];
            $pars['device_identifier']['uuid'] = $identifier['uuid'];
            $pars['device_identifier']['minor'] = $identifier['minor'];
        }
        
        $pars['page_ids'] = $pages;
        $pars['bind'] = $bind ? 1 : 0;
        $pars['append'] = 0;
        $resp = ihttp_post($url,json_encode($pars));
        if(is_error($resp)){
            return $resp;   
        }
        $ret = @json_decode($resp['content'],true);
        if(is_array($ret) && $ret['errcode']=='0'){
            return true;    
        }
        return error(-1,$resp['content']);
    }

    /**
     * 查询一个设备的详细信息
     * 返回设备信息, 参照表结构
     *
     * @param $identifier string|array
     * @return row|empty
     */
    public function queryDevice($identifier) {
        $token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/shakearound/device/search?access_token={$token}";    
        $pars = array();
        if(is_string($identifier)){
            $pars['device_identifiers'][0]['device_id'] = intval($identifier);    
        } else {
            $pars['device_identifiers'][0]['device_id'] = intval($identifier['device_id']);
            $pars['device_identifiers'][0]['uuid'] = $identifier['uuid'];
            $pars['device_identifiers'][0]['major'] = $identifier['major'];
            $pars['device_identifiers'][0]['minor'] = $identifier['minor'];
        }
        $resp = ihttp_post($url,json_encode($pars));
        if(is_error($resp)){
            return $resp;   
        }
        $ret = @json_decode($resp['content'],true);
        if(is_array($ret) && !empty($ret['data']) ){
            $r = $ret['data']['devices'][0];
            return $r;    
        }
        return error(-1,$resp['content']);
    }

    /**
     * @param $page array()
     *          title           - 标题
     *          description     - 副标题
     *          url             - 图片
     *          comment         - 备注信息
     *          icon            - 图标
     * @return int|error
     */
    public function createPage($page) {
        $token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/shakearound/page/add?access_token={$token}";
        $pars = array();
        $pars['title'] = $page['title'];
        $pars['description'] = $page['description'];
        $pars['page_url'] = $page['url'];
        $pars['comment'] = $page['comment'];
        $image = $this->mediaUpload($page['image']);
        if(is_error($image)) {
            return $image;
        }
        $pars['icon_url'] = $image;
        $resp = ihttp_post($url,json_encode($pars));
        if(is_error($resp)){
            return $resp;    
        }
        $ret = @json_decode($resp['content'],true);
        if(is_array($ret) && !empty($ret['data'])){
            $r = $ret['data']['page_id'];
            return $r;
        }
        return error(-1,$resp['content']);
    }

    /**
     * @param $id
     * @param $page array()
     *              title           - 标题
     *              description     - 副标题
     *              url             - 图片
     *              comment         - 备注信息
     *              icon            - 图标
     * @return int|error
     */
    public function modifyPage($id, $page) {
        $token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/shakearound/page/update?access_token={$token}";    
        $pars = array();
        $pars['title'] = $page['title'];
        $pars['description'] = $page['description'];
        $pars['page_url'] = $page['url'];
        $pars['comment'] = $page['comment'];
        $image = $this->mediaUpload($page['image']);
        if(is_error($image)) {
            return $image;
        }
        $pars['icon_url'] = $image;
        $pars['page_id'] = intval($id);
        $resp = ihttp_post($url,json_encode($pars));
        if(is_error($resp)){
            return $resp;    
        }
        $ret = @json_decode($resp['content'],true);
        if(is_array($ret) && !empty($ret['data'])){
            $r = $ret['data']['page_id'];
            return $r;
        }
        return error(-1,$resp['content']);
    }
    
    public function deletePage($id) {
        $token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/shakearound/page/delete?access_token={$token}";
        $pars = array();
        $pars['page_ids'][0] = intval($id);
        $resp = ihttp_post($url,json_encode($pars));
        if(is_error($resp)){
            return $resp;
        }
        $ret = @json_decode($resp['content'],true);
        if(is_array($ret) && $ret['errcode']=='0'){
            return true;
        }
        return error(-1,$resp['content']);
        
    }

    /**
     * 上传图片素材
     * @param $file string 要上传的文件
     * @return string
     */
    public function mediaUpload($file) {
        $file = IA_ROOT . '/attachment/' . $file;
        $token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/shakearound/material/add?access_token={$token}";
        $body = array();
        if(function_exists('curl_file_create')) {
            $body['media'] = curl_file_create($file);
        } else {
            $body['media'] = '@' . $file;
        }
        $resp = @ihttp_request($url, $body);
        if(is_error($resp)){
            return $resp;
        }
        $ret = @json_decode($resp['content'],true);
        if(is_array($ret) && $ret['errcode']=='0'){
            return $ret['data']['pic_url'];
        }
        return error(-1,$resp['content']);
    }
    
    public function getCurrent() {
        global $_W, $_GPC;
        if(empty($_GPC['ticket'])) {
            return error(-1, '<h4>非常抱歉, 你必须到我们现场才能参加活动. <br>详细情况, 请查看活动说明</h4>');
        }
        $token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/shakearound/user/getshakeinfo?access_token={$token}";
        $pars = array();
        $pars['ticket'] = $_GPC['ticket'];
        $resp = ihttp_post($url, json_encode($pars));
        if(is_error($resp)) {
            return $resp;
        }
        $ret = @json_decode($resp['content'], true);
        if(is_array($ret) && $ret['errcode'] == '0') {
            $d = new Device();
            $device = $d->getOne($ret['data']['beacon_info']);
            if(empty($device)) {
                return error(-1, '<h4>系统错误, 您在未知的活动地点</h4>');
            }
            $r = array();
            $r['device'] = $device;
            $r['openid'] = $ret['data']['openid'];
            $r['distance'] = $ret['data']['beacon_info']['distance'];

            return $r;
        }
        if($ret['errcode'] == '9001003') {
            return error(-1, '<h4>非常抱歉, 页面已过时, 请重新摇一摇进入活动. <br>详细情况, 请查看活动说明</h4>');
        }
        return error(-1, $resp['content']);
    }
}