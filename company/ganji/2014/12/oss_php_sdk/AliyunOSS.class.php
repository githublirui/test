<?php

/**
 * 
 * 阿里云OSS服务类
 * @desc 文件操作
 * @author Lirui <649037629@qq.com>
 * 
 */
//ACCESS_ID
define('OSS_ACCESS_ID', 'ukyp0cxUs1DrH4vR');
//ACCESS_KEY
define('OSS_ACCESS_KEY', 'apD35VSHYJHzWf5ETuWlfqOWfuAA5o');
//是否记录日志
define('ALI_LOG', FALSE);
//自定义日志路径，如果没有设置，则使用系统默认路径，在./logs/
//define('ALI_LOG_PATH','');
//是否显示LOG输出
define('ALI_DISPLAY_LOG', FALSE);
//语言版本设置
define('ALI_LANG', 'zh');
define('OSS_NAME', 'oss-sdk-php');
define('OSS_VERSION', '1.1.6');
define('OSS_BUILD', '201210121010245');
include 'sdk.class.php';

class AliyunOSS {
    
}
