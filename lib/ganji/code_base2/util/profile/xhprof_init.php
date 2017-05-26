<?php
/**
 * @Copyright (c) 2011 Ganji Inc.
 * @brief                XHProf性能采集模块,直接include此文件即可
 * @author              caifeng
 * @file                 /code_base2/util/profile/xhprof_init.php
 * @version             $Revision: $
 * @modifiedby           $Author:  $
 * @lastmodified         $Date:  $
 * 
 * 初始化xhprof性能采集模块
 */

if (defined('XHPROF_PROFILE_COUNT')) {
    $profile_count = XHPROF_PROFILE_COUNT;
} else {
    $profile_count = 0; // 禁止性能采集
}

if (($profile_count > 0) && (mt_rand(1, $profile_count) == 1) && extension_loaded('xhprof')) {
    xhprof_enable(XHPROF_FLAGS_NO_BUILTINS); //不包括内部函数
    #xhprof_enable();
    register_shutdown_function("ganji_xhprof_save");
}

/**
 * 采集数据，并输出到日志系统
 */
function ganji_xhprof_save() {
    $xhprof_data = xhprof_disable();
    $xhprof_data['meta'] = array( 
        'server' => $_SERVER,
        );
    include_once (dirname(__FILE__) . '/../log/Logger.class.php');
    $data = @json_encode($xhprof_data);
    if( $data ) {
        $zdata = gzcompress( $data );
        Logger :: logRaw('z:' . $zdata, ScribeLogger :: INFO, 'xhprof.' . Logger::getConfig()->getBaseCategory() );
    }
}
