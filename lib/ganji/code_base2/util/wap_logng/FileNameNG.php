<?php
//ini_set('display_errors', 1);
//error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

require_once dirname(__FILE__)."/coreng.config.php";

/**
 * 获取日志文件名称
 */
class FileNameNG
{
    /**
     * 默认线上wap服务器日志存储文件路径;
     * @var str
     */
    const _defaultFilebase = '/data/waplog/mobilelog/wapvZZ/';


    /**
     * 存储文件名称
     * @param str $webHandle
     */
    public static function getFileName($webHandle) {
        $_webHandle = strtolower($webHandle);//转换成小写
        $basepath = self::getBaseFilepath($_webHandle) ;
        if(substr($basepath, strlen($basepath)-1) == '/'|| substr($basepath, strlen($basepath)-1) == '\\') {
            //判断最后一位是不是‘/’|‘\’
        }else {
            $basepath = $basepath . '/';
        }
        $prefix = date('Y/m/Y-m-d_');
        $fileNameNO = self:: getFileTimespan();  //文件序列号
        $fileFullName = $basepath . $prefix . $fileNameNO . '.log';
        return $fileFullName;
    }


    /**
     * 通过计算一小时产生多少个文件<br/>
     * 返回当前文件序列号
     */
    private static  function getFileTimespan()
    {
        //基本时间单位一小时
        $timespan=(number_format((LogCoreConfigNG::logFileMaxHoldSecond/3600),3));
        $h = floor((date('H')+1)/$timespan);
        return $h;
    }


    /**
     * webHandler如果是wapv2 、wap ,请相应的文件config配置是WapLogV2_BasePath、WapLog_BasePath
     * @return string
     */
    private static function getBaseFilepath($_webHandle)
    {
        //通过handle值来拼接出路径
        $key=$_webHandle.'_BasePath';
        if(array_key_exists($key, LogCoreConfigNG::$LogBasePath)){
            if(!empty(LogCoreConfigNG::$LogBasePath[$key])){
                return  LogCoreConfigNG::$LogBasePath[$key];
            }
        }
        return   self::_defaultFilebase;
    }
}
?>
