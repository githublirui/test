<?php

/** 
 * 电话相关类库
 * @copyright (c) 2011 Ganji Inc.
 * @file      PhoneNamespace.class.php
 * @author    zhoufan <zhoufan@ganji.com>
 * @date      2011-5-12
 */

/**
 * @brief 电话相关类库
 */
class PhoneNamespace {

    /**
     * @brief 由电话号码获得城市信息（目前只支持手机号码）
     * @param string $phone
     * @return array
     *  - phone 电话号码
     *  - prefix 电话前缀（用前缀判断归属地）
     *  - province_id 省id
     *  - province_script_index 省script_index
     *  - province_name 省名
     *  - city_id 城市id
     *  - city_script_index 城市script_index
     *  - city_name 城市名
     */
    public static function phone2City($phone) {
        if (!$phone)   return false;
        require_once GANJI_CONF . '/ServiceConfig.class.php';
        $service_url    = ServiceConfig::$SERVICE_HOST . '/fcgi/mobile2city/query?act=mobile2city&mobile=' . $phone;
        $stream_setting    = stream_context_create(
            array(
                'http'  => array(
                    'timeout'   => 2,
                ),
            )
        );
        $ret    = @file_get_contents($service_url , 0 , $stream_setting);
        $info   = explode("\t" , $ret);
        
        if ($info[0] != (int) $info[0]) {
            return false;
        }
        elseif ((int) $info[5] == 0) {
            return false;
        }
        
        return array(
            'phone'                 => $phone,
            'prefix'                => $info[0],
            'province_id'           => $info[2],
            'province_script_index' => $info[3],
            'province_script_name'  => $info[1],
            'city_id'               => $info[5],
            'city_script_index'     => $info[6],
            'city_name'             => $info[4],
        );
        
    }

    /*
     *FUWU-5287
     *1.收到反馈010-95081拨打会提示空号，直接拨打95081可以正常接通；
     *2.现需要对类似的5位短电话号码去掉前面的区号；
     *3.影响范围：wap和app的电话拨打按钮。
     *@param
     * $phone:010-95081
     * */
    public static function formatphone5($phone) {
        if($phone){
            $phoneInfo = explode('-', $phone);
            if (count($phoneInfo) <= 2) {//避免处理 400-12345-6789这样的
                if (strlen($phoneInfo[1]) == 5) {
                    return $phoneInfo[1];
                } else {
                    return $phone;
                }
           } else {
            return $phone;
           }
        }
        return false;
    }
    
    /**
     * 处理手机号  add by xuhuangjuan@ganji.com
     * FUWU-5886 WAP拨打电话的电话号码格式问题处理
     * 如：110-123456789-120 改进如下格式110123456789,120即去掉第一个'-'，将第二个'-'改为','，如果有第三段'-'，暂时保持不变
     * @param unknown_type $phone
     * @Modify quyong@ganji.com 
     * @Modify Date 2014-05-19
     * @Modify Reason 兼容接受多个电话的处理
     */
    public static function dealTelPhone($phone){

        if(empty($phone)) {
            return false;
        }
        $phonesList = explode(',',$phone);
        //取第一个电话号码
        $phone = $phonesList[0];

    	//号码中无-,直接原样返回
    	if(stripos($phone,'-') === false){
    		return $phone;
    	}
    	//号码为400或800电话,将-去除返回
    	if(stripos($phone,'4') === 0 || stripos($phone,'8') === 0){
    		return str_replace('-','',$phone);
    	}
    	//固定电话的处理
    	$pattern = array('/(\d{3,4})-(\d{7,8})-(\d{1,4})/','/(\d{3,4})-(\d{7,8})/','/(\d{7,8})-(\d{1,4})/');
    	$replacement = array('${1}${2},${3}','${1}${2}','${1},${2}');
    	return preg_replace($pattern, $replacement, $phone);
    }

}
