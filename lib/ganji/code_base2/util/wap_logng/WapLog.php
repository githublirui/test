<?php
require_once dirname(__FILE__)."/webng.config.php";
require_once dirname(__FILE__)."/LogWriterNG.php";

class WapLog
{
    public static function log($webhandle, $utmUrl)
    {
        $arr = explode('?', $utmUrl);
        if(isset($arr) && count($arr)>1){
            WapLog::uriAction($webhandle, $arr[1]);
        }else {
            WapLog::uriAction($webhandle, $utmUrl);
        }
    }
    public static function logNoFileRecord($webhandle, $utmUrl)
    {
    	$arr = explode('?', $utmUrl);
    	if(isset($arr) && count($arr)>1){
    		$str=WapLog::uriActionNoFileRecord($webhandle, $arr[1]);
    	}else {
    		$str=WapLog::uriActionNoFileRecord($webhandle, $utmUrl);
    	}
    	return   $str;
    }
    /**
     * 不写入文件uriAction
     * @param str $webhandle
     * @param str $utmUrl
     */
    public static function uriActionNoFileRecord($webhandle, $utmUrl)
    {
    	// 暂时 不记录 spider的日志
    	if (defined('IS_WAP_SPIDER') && IS_WAP_SPIDER === true) {
    		return;
    	}
    
    	//TODO find some way up to tell someone
    	if(!isset(LogWebConfigNG::$LogFields[$webhandle])) {
    		return;
    	}
    	$utmUrl = html_entity_decode($utmUrl, ENT_NOQUOTES);
    	parse_str($utmUrl,$utmUrlArr);
    
    	$content = WapLog::getLogHeader($webhandle);
    	$log_fields = LogWebConfigNG::$LogFields[$webhandle];
    	if(!empty($utmUrlArr['utmvisitorid'])){
    		$utmUrlArr['utmuuid']=$utmUrlArr['utmvisitorid'];
    	}
    	foreach($log_fields as $key => $value){
    		if(array_key_exists($value, $utmUrlArr)){
    
    			$str = urldecode($utmUrlArr[$value]);
    			//增加一次cookie able 判断
    			if($value=='utmisCookieble'&&!$str)
    			{
    				$str=self::_isSupportCookie();
    			}
    			$str = WapLog::sanitize($str);
    		} else{
    			$str = "-";
    		}
    		//echo $key . $value . $str;
    		$content .= "\t" . ( ($str != "0" && empty($str)) ? "-" : $str);
    	}
    	$content .= "\n";
    	return $content;
    	//     	$writer = new LogWriterNG($webhandle);
    	//     	$writer->run($content);
    }
    public static function uriAction($webhandle, $utmUrl)
    {
    	$content=self::uriActionNoFileRecord($webhandle, $utmUrl);
    
        $writer = new LogWriterNG($webhandle);
        $writer->run($content);
    }


    /**
     * log 文件头部，一般默认返回date(y-m-d h:i:s)格式的表头
     * @return string
     */
    private static function getLogHeader($webhandle)
    {
        $dateHeader = date('Y-m-d H:i:s');
        switch($webhandle){
            case "waplog": # not waplogv1
                return  "waplog V1.0\t" . $dateHeader;
            default:
                return $dateHeader;
        }
    }


    /**
     * 排除字符中 '\t' '\r' '\n' 等关键字
     */
    private static function sanitize($str) {
        $search = array("\t", "\r", "\n");
        $replace = array("%09", "%0D", "%0A");
        $str= str_replace($search, $replace, $str);
        return $str;
    }
	/**
	 * 直接获取枚举cookies值，若为空值有可能是第一次访问或者是不支持
	 * */
	private static function _isSupportCookie()
	{
		$isSupport='0';
		$cookie = $_COOKIE['cityDomain'];
		if(!empty($cookie))
		{//assert all page support cookie for city 
			return '1';
		}
		return $isSupport;
	}
}
