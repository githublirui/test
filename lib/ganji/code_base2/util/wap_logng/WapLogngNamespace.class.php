<?php
//__wap_is_uc_20111226 is in WapPage
define("COOKIE_NAME_V1", "__utmmobile");
define("COOKIE_NAME_V2", "__utmganji_v20110909");
define("COOKIE_NAME_DZ", "__utmganji_v20120411");
define("COOKIE_NAME_TOUCH", "__utmganji_v201207141");
define("COOKIE_NAME_HTML5", "__utmganji_v201207142");
define("COOKIE_PATH", "/");
if(stripos($_SERVER['HTTP_HOST'],'ganji.cn')!==false)
{
    define("WAP_COOKIE_DOMIAN", ".ganji.cn");
}else
{
    define("WAP_COOKIE_DOMIAN", ".ganji.com");
}
define("COOKIE_USER_PERSISTENCE", 63072000);
if(!defined('COOKIE_NAME_CA_NAME'))
{
    //define("COOKIE_NAME_CA_NAME", "__utmganji_wap_caname");//ca_name
    //     define("COOKIE_NAME_IFID", "__utmganji_wap_ifid");
    define("COOKIE_NAME_CA_NAME", "__utmganji_wap_caname2");//ca_name
}
// 1x1 transparent GIF
$GIF_DATA = array(
    chr(0x47), chr(0x49), chr(0x46), chr(0x38), chr(0x39), chr(0x61),
    chr(0x01), chr(0x00), chr(0x01), chr(0x00), chr(0x80), chr(0xff),
    chr(0x00), chr(0xff), chr(0xff), chr(0xff), chr(0x00), chr(0x00),
    chr(0x00), chr(0x2c), chr(0x00), chr(0x00), chr(0x00), chr(0x00),
    chr(0x01), chr(0x00), chr(0x01), chr(0x00), chr(0x00), chr(0x02),
    chr(0x02), chr(0x44), chr(0x01), chr(0x00), chr(0x3b)
);
/**
 * 提供日志统计基础方法
 *
 * @author    wangjian
 * @touch     2012-11-28
 * @category  wap
 * @package   WapLogngNamespace.class.php
 * @version   0.1.0
 * @copyright Copyright (c) 2005-2012 GanJi Inc. (http://www.ganji.com)
 */
class  WapLogngNamespace{
    /**
     * 存储在session key
     * @var str
     */
    const CA_INFO_SESSION_KEY="_wap__utmganji_wap_caInfo_V2";
    
    
	/**
	 * ga_v2.php日志统计
	 * @param array $params
	 *    -domain [cityInfo.domain]
	 *       eg:bj
	 *    -gjch   [str]
	 *       eg://index@isappdown=1
	 *    -version  [str]
	 *       eg:WapPage::visitor['version']
	 *    -gjeval   [str]
	 *       eg:WapPage::getGjeval()
	 * @param null $retUtmUrlArr 
	 *    返回    $utmUrlArr
	 * @return string
	 */
    public static function GetAnalyticsImageUrl($params,&$retUtmUrlArr=null){
    	$utmUrlArr =self::preTrackPageViewV2();
    	$utmUrlArr["utmcity"]  = $params['utmcity'] ? $params['utmcity'] : '-';
    	$utmUrlArr["utmgjver"] = $params['utmgjver'];
    	$utmUrlArr['utmgjeval']= $params['utmgjeval'];
    	$utmUrlArr["utmgjch"]  = $params['utmgjch']?$params['utmgjch']:'-';
    	$utmUrlArr["utmgjch"]  = urlencode( $utmUrlArr["utmgjch"]);//传参之前 加密
    	
    	$utmUrlArr["utmgjgc"]  = $params['utmgjgc']?$params['utmgjgc']:'-';
    	$utmUrlArr["utmgjgc"]  = urlencode( $utmUrlArr["utmgjgc"]);//传参之前 加密 for new gc
    	
    	$utmUrlArr["utmgjuser_id"] = $params["utmgjuser_id"];//user_id
    	
    	$utmUrlArr["utmpvid"] = md5($utmUrlArr['utmuuid']."_".$utmUrlArr['utmsid'].'_'.time());
    	
    	$utmUrlArr['utmuuid']  = $utmUrlArr['utmvisitorid'];//uuid
    	$utmUrlArr['utmn']= rand(0, 0x7fffffff);
    	$utmUrlArr["ads"] = '';
    	$utmGifLocation = "http://wap.ganji.com/ga_v2.php";
    	$utmUrl = $utmGifLocation . "?" . http_build_query($utmUrlArr);
    	
    	$retUtmUrlArr=$utmUrlArr;
    	
    	return htmlspecialchars($utmUrl, ENT_NOQUOTES);
    }
    
    /**
     * wapp.gif
     * @param array $utmUrlArr 
     *        -see GetAnalyticsImageUrl.$retUtmUrlArr
     * @param  bool $isOnlyGetLogstr
     *        return only logstr?
     * @return boolean|string
     */
    public static function GetAnalyticsImageUrlRequestAnaTeam($utmUrlArr,$isOnlyGetLogstr=false){
    	if(defined('IS_WAP_SPIDER')&&IS_WAP_SPIDER===true){
    		return false;
    	}
    	if(!$utmUrlArr){//不存在
    		return false;
    	}
    	$utmGifLocation = "http://analytics.ganji.com/wapp.gif";
    	$utmUrl = $utmGifLocation . "?" . http_build_query($utmUrlArr);
    	$utmUrl= htmlspecialchars($utmUrl, ENT_NOQUOTES);
    	//传递urlencode值
    	require_once dirname(__FILE__) . '/WapLog.php';
    	if(method_exists('WapLog','logNoFileRecord')) {
    		$tontents=WapLog::logNoFileRecord("waplogv2", $utmUrl);
    		if($isOnlyGetLogstr){
    			return $tontents;
    		}
    		$goUrl=$utmGifLocation."?logstr=".rawurlencode($tontents);
    		return $goUrl;
    	}else{
    		return $utmUrl;//兼容
    	}
    }
    //use HTTP_HOST instead of SERVER_NAME
    public static  function  curPageURL()
    {
        $pageURL = 'http';
        if ($_SERVER["HTTPS"] == "on") {
            $pageURL .= "s";
        }
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            //$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
            //$pageURL .= $_SERVER["HTTP_HOST"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
            $pageURL .= $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
        } else {
            //$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
            $pageURL .= $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }

    // Writes the bytes of a 1x1 transparent gif into the response.
    public static function writeGifData() {
        global $GIF_DATA;
        header("Content-Type: image/gif");
        header("Cache-Control: " .
            "private, no-cache, no-cache=Set-Cookie, proxy-revalidate");
        header("Pragma: no-cache");
        header("Expires: Wed, 17 Sep 1975 21:32:10 GMT");
        echo join($GIF_DATA);
    }
    //     // Writes the bytes of a 1x1 transparent gif into the response.
    //     public static  function  writeGifData()
    //     {
    //         //    global $GIF_DATA;
    //         //    header("Content-Type: image/gif");

    //         header("Content-Type: text/css");
    //         header("Cache-Control: " . "private, no-cache, no-cache=Set-Cookie, proxy-revalidate");
    //         header("Pragma: no-cache");
    //         header("Expires: Wed, 17 Sep 1975 21:32:10 GMT");
    //         //echo join($GIF_DATA);
    //         echo '';
    //     }

    //去掉IP组后一个分组，以保护用户隐私。
    public static  function  maskIP($ip)
    {
        $regex = "/^([^.]+\.[^.]+\.[^.]+\.).*/";
        if (preg_match($regex, $ip, $matches)) {
            return $matches[1] . "0";
        } else {
            return "-";
        }
    }


    // The last octect of the IP address is removed to anonymize the user.
    public static  function  getIP($remoteAddress)
    {
        require_once CODE_BASE2.'/util/http/HttpNamespace.class.php';
        $codeIp=HttpNamespace::getIp(false);
        if(!empty($codeIp))
        {
            return $codeIp;
        }
        if (empty($remoteAddress)) {
            return "-";
        }
        //return maskIp($remoteAddress);
        return $remoteAddress;
    }

    /**
     * 加密后google id
     * @param unknown_type $userAgent
     * @param unknown_type $cookie
     * @param unknown_type $account
     * @param unknown_type $guid
     */
    public static  function  getVisitorIdEncrpt($userAgent, $cookie, $account="", $guid="")
    {
        $decrypted_id=getVisitorId($userAgent, $cookie, $account, $guid);
        if(is_numeric($decrypted_id))
        {
            require_once CODE_BASE2.'/util/wapDecrpty/WapCrypt3DesStaticNamespace.class.php';
            return urlencode(WapCrypt3DesStaticNamespace::encryptStatic($decrypted_id));
        }
    }
    /**
     * visitorid=uuid
     * uuid="0x"+len(A:16)+len(B:15~18) 
     * cookie校验算法
     */
    private static  function _createUuidCheck($userAgent, $cookie, $account="", $guid="") {
    	if(strlen($cookie)==18){//old
    		$pre_fix=$cookie;
    	}else{
	    	//new
	    	$message = "";
	    	if (!empty($guid)) {
	    		// Create the visitor id using the guid.
	    		$message = $guid . $account;
	    	} else {
	    		// otherwise this is a new user, create a new random id.
	    		$message = $userAgent . uniqid(rand(0, 0x7fffffff), true);
	    	}
	    	$md5String = md5($message);
	    	$pre_fix= "0x" . substr($md5String, 0, 16);
    	}
    	//check
    	$time = sprintf("%f", microtime (true)*1000);
    	$time = explode ( ".", $time );
    	$tm = $time [0];    //获得毫秒
    	$rm = rand(1000, 9999);
    	
    	$str =$tm . rand(1, 9);
    	$str=strrev($str);
    	$str = ($str + $rm) . $rm;
    	$p =  base_convert($str, 10, 16);  //转化为16进制
    	return $pre_fix.$p;
    }
    /**
     * 
     * @param str $uuid_hex_str 
     *  eg:0x1f0e1b196e0efd99dcc76816af7c6be
     *  wiki:公共组/移动互联网/日志统计/uuid
     * @return false|array($uuid_small,$timestamp)
     * @see $timestamp must little than now()
     */
    public static function CheckUuidByWap($uuid_hex_str){
    	if(!$uuid_hex_str || strlen($uuid_hex_str)<=18){
    		return $uuid_hex_str;
    	}
    	$uuid=substr($uuid_hex_str, 0, 18);//协议中，uuid前18位是真实值，以后全归校验
    	$check=substr($uuid_hex_str, 18 );
    
    	$tmp=base_convert('0x'.$check, 16, 10);
    	$random=substr($tmp ,strlen($tmp)-4,4);
    	$mix=substr($tmp ,0,strlen($tmp)-4);
    	$mix=$mix-$random;
    	$mix=strrev($mix);
    	$time=substr($mix,0,strlen($mix)-1);
    	$time=intval($time/1000);
    	return array($uuid,$time);
    }
    // Generate a visitor id for this hit.
    // If there is a visitor id in the cookie, use that, otherwise
    // use the guid if we have one, otherwise use a random number.
    public static  function  getVisitorId($userAgent, $cookie, $account="", $guid="")
    {
    	//校验$cookie合法性
    	//is_hex && len>18
    	if(!is_numeric($cookie)||strlen($cookie)<=18){
    		$old_cookie=$cookie;
    		$cookie='';
    	}
        if(!class_exists('SessionNamespace')){
            //session
            require_once CODE_BASE2.'/util/session/SessionNamespace.class.php';
        }
        //$_GET
        $encrypted=$_GET['googleUid'];
        if(!empty($encrypted))
        {
            require_once CODE_BASE2.'/util/wapDecrpty/WapCrypt3DesStaticNamespace.class.php';
            $encrypted=urldecode($encrypted);
            $validateVisitorId=WapCrypt3DesStaticNamespace::decryptStatic($encrypted, false);
            if(!empty($validateVisitorId)&&$cookie!=$validateVisitorId){
                    //get 参数最高级
                    setrawcookie(COOKIE_NAME_V2, $validateVisitorId, $timeStamp + COOKIE_USER_PERSISTENCE, COOKIE_PATH,WAP_COOKIE_DOMIAN);
                    SessionNamespace::setValue('GWapGoogleVisitorId', $validateVisitorId);
                    return $validateVisitorId;
                //else 和cookie  相同，返回cookie 值
            }
        }
        // If there is a value in the cookie, don't change it.
        //cookie
        if (!empty($cookie)) {
            return $cookie;
        }
        $idvalue= SessionNamespace::getValue('GWapGoogleVisitorId');
        if($idvalue){
			//校验$cookie合法性
			//is_hex && len>18
			if(is_numeric($idvalue)&&strlen($idvalue)>18){
				return $idvalue;
			}
        }
		//create  uuid
        $tp=self::_createUuidCheck($userAgent, $old_cookie, $account, $guid);

//         require_once dirname(__FILE__).'/WapAntispamNamescape.class.php';
//         WapAntispamNamescape::SetSpiderMec($tp);
        SessionNamespace::setValue('GWapGoogleVisitorId', $tp);
        return $tp;
    }


    //预获取相关统计参数，可以使用array_merge等覆盖
    public static  function  preTrackPageViewV1()
    {
        $utmUrlArr=array();
        $utmUrlArr['utmhn'] = urlencode($_SERVER["HTTP_HOST"]);
        $utmUrlArr["utmua"] = urlencode($_SERVER["HTTP_USER_AGENT"]);
        $utmUrlArr["utmip"] =self:: getIP($_SERVER["REMOTE_ADDR"]);
        //TODO this should be done in low level
        $utmUrlArr['utmr'] = urlencode($_SERVER ["HTTP_REFERER"] ? $_SERVER ["HTTP_REFERER"] : "-");
        $utmUrlArr['utmp'] = urlencode($_SERVER ["REQUEST_URI"]);

        $utmUrlArr["guid"] = "ON";
        $utmUrlArr["timestamp"] = time() ;
        $utmUrlArr["utmn"] = rand ( 0, 0x7fffffff );

        $cookie = $_COOKIE[COOKIE_NAME_V1];
        $account = $_GET["utmac"];
        //TODO if this has affect on getVisitorId
        $userAgent = $_SERVER["HTTP_USER_AGENT"] ? $_SERVER["HTTP_USER_AGENT"] : "-";
        $visitorId =self:: getVisitorId($userAgent, $cookie, $account, $_SERVER["HTTP_X_DCMGUID"]);
        $timeStamp = time();
        setrawcookie(COOKIE_NAME_V1, $visitorId, $timeStamp + COOKIE_USER_PERSISTENCE, COOKIE_PATH,WAP_COOKIE_DOMIAN);
        $utmUrlArr["utmvid"] =  $visitorId;

        //         if(array_key_exists('ifid', $_GET))
        //         $utmUrlArr['utmifid'] = urlencode($_GET["ifid"]);
        $ifid=self::_getIfid();
        if(!empty($ifid)){
            $utmUrlArr['utmifid'] = $ifid;
        }
        if(array_key_exists('cate', $_GET))
            $utmUrlArr['utmn'] = $_GET["utmn"];

        if(array_key_exists('cate', $_GET))
            $utmUrlArr['cate'] = $_GET["cate"];
        if(array_key_exists('majcate', $_GET))
            $utmUrlArr['majcate'] = $_GET["majcate"];
        if(array_key_exists('mincate', $_GET))
            $utmUrlArr['mincate'] = $_GET["mincate"];

        if(array_key_exists('channel', $_GET))
            $utmUrlArr['channel'] = $_GET["channel"];
        else if(array_key_exists('from', $_GET))
            $utmUrlArr['channel'] = $_GET["from"];

        if(array_key_exists('appid', $_GET))
            $utmUrlArr['appid'] = $_GET["appid"];

        return $utmUrlArr;
    }


    //记录日志，写入本地文件
    public static  function  writeToLocalV1($utmUrl)
    {
        require_once(dirname(__FILE__)."/WapLog.php");
        WapLog::log("waplog", $utmUrl);
    }


    public static  function  InvokeWapLogV1($arr)
    {
        /*$utmGifLocation = "/ga.php";
         $utmUrlArr = array_merge(preTrackPageViewV1(), $arr);
        //print_r($utmUrlArr);
        $utmUrl = $utmGifLocation . "?" . http_build_query($utmUrlArr);
        writeToLocalV1($utmUrl);
        */
    }


    /**
     * 为了AB测试， 增加GJ.LogTracker.gjchver变量。该值从‘A-Z’
     *果页面中没有定义该变量，或者该变量不合法，则缺省为’A’
     **/
    public static  function  getGjeval($value='')
    {
        if (empty($value)) {
            $evalValue = "A";
        }
        return urlencode($evalValue);
    }



    /**
     * 直接获取枚举cookies值，若为空值有可能是第一次访问或者是不支持
     * */
    public static  function  isSupportCookie()
    {
        $CookieNameArray = array(COOKIE_NAME_V1, COOKIE_NAME_V2, 'citydomain', 'ganjiWapCookieDataStat');
        $isSupport='0';
        foreach ($CookieNameArray as $cookiename) {
            if (isset( $_COOKIE[$cookiename])) {
                $isSupport = '1';
                break;
            }
        }
        return $isSupport;
    }


    //预获取相关统计参数，可以使用array_merge等覆盖
    public static  function  preTrackPageViewV2()
    {
        $utmr = urlencode($_SERVER ["HTTP_REFERER"]);
        $utmua = urlencode($_SERVER["HTTP_USER_AGENT"]);
        $utmip =self:: getIP($_SERVER["REMOTE_ADDR"]);

        $utmp = urlencode(self::curPageURL()); //NOTE actually this is url, not path
        $supportCookie = self::isSupportCookie();
        $gjeval =self:: getGjeval();

        $cookie = $_COOKIE[COOKIE_NAME_V2];

        $userAgent = $_SERVER["HTTP_USER_AGENT"] ? $_SERVER["HTTP_USER_AGENT"] : "-";
        $visitorId =self:: getVisitorId($userAgent, $cookie);
        $sid = session_id();

        $timeStamp = time();
        setrawcookie(COOKIE_NAME_V2, $visitorId, $timeStamp + COOKIE_USER_PERSISTENCE, COOKIE_PATH,WAP_COOKIE_DOMIAN);
        if(!class_exists('UAInterface')){
            require_once(CODE_BASE2."/app/ua/UAInterface.class.php");
        }
        $uaHandle = new UAInterface($_SERVER["HTTP_USER_AGENT"]);
        $uaArr = $uaHandle->getInfoFromUA();
        if(empty($_SERVER["HTTP_USER_AGENT"])) {
            $uaArr['plateform'] = "";
            $uaArr['browser'] = "";
        } else {
            if(empty($uaArr['plateform']))
                $uaArr['plateform'] = "-";
            if(empty($uaArr['browser']))
                $uaArr['browser'] = "-";
        }
        //$parameters = "os_name:" . $uaArr['plateform'] . "|os_version:" . $uaArr['p_version'] .
        //    "|browser_name:" . $uaArr['browser'] . "|browser_version:" . $uaArr['b_version'];
        $parameters = "on:" . $uaArr['plateform'] . "|ov:" . $uaArr['p_version'] .
        "|bn:" . $uaArr['browser'] . "|bv:" . $uaArr['b_version'];
        
        $utmUrlArr = array(
            //"utmua" => $utmua,
        	"utmua" => '-',
            "utmip" => $utmip ,
            "utmp" => $utmp,
            "utmr" => $utmr,
            "utmisCookieble" => $supportCookie,
            "utmvisitorid" => $visitorId,
            "utmsid" => $sid,
            "utmgjeval" => $gjeval,

            "parameters" => $parameters,
        );

        //         if(array_key_exists('ifid', $_GET))
        //         $utmUrlArr['utmifid'] = urlencode($_GET["ifid"]);
        $ifid=self::_getIfid();
        if(!empty($ifid)){
            $utmUrlArr['utmifid'] = $ifid;
        }
        if(array_key_exists('cate', $_GET))
            $utmUrlArr['utmn'] = $_GET["utmn"];

        if(array_key_exists('cate', $_GET))
            $utmUrlArr['cate'] = $_GET["cate"];
        if(array_key_exists('majcate', $_GET))
            $utmUrlArr['majcate'] = $_GET["majcate"];
        if(array_key_exists('mincate', $_GET))
            $utmUrlArr['mincate'] = $_GET["mincate"];

        //NOT in V2(will be extracted in getOrganicInfo)
        /*
         if(array_key_exists('channel', $_GET))
            $utmUrlArr['channel'] = $_GET["channel"];
        else if(array_key_exists('from', $_GET))
            $utmUrlArr['channel'] = $_GET["from"];

        if(array_key_exists('appid', $_GET))
            $utmUrlArr['appid'] = $_GET["appid"];
        */

        $utmUrlArr['utmorganicinfo'] = self::getOrganicInfo();

        return $utmUrlArr;
    }


    /**
     * 1,
     * @param string $ca_name
     * @param string $ca_source
     * @param string $ca_kw
     * @param string $ca_id
     * @return string
     */
    public static  function  getOrganicInfo($ca_name='', $ca_source='', $ca_kw='', $ca_id='')
    {
        require_once CODE_BASE2.'/util/session/SessionNamespace.class.php';

        return self::_getOrganicInfoV2();

        $info=self::_getCaInfo();

        $ca_source=$info['ca_source'];$ca_name=$info['ca_name'];
        $ca_kw=$info['ca_kw'];$ca_id=$info['ca_id'];
        $castr = "ca_source=" . (empty($ca_source)? '-' : urlencode($ca_source)) .
            "&ca_name=" . (empty($ca_name) ? '-' : urlencode($ca_name)) .
            "&ca_kw=" .  (empty($ca_kw) ? '-' : urlencode($ca_kw)) .
            "&ca_id=" . (empty($ca_id)? '-' : urlencode($ca_id)) ;
        return urlencode($castr) ;
    }
    /**
     * 将渠道4元组信息设置到storage(cookie?session?)
     * @param array $_caInfo
     * 	-ca_source str |null
     * 	-ca_name str |null
     * 	-ca_kw str |null
     * 	-ca_id str |null
     * @return boolean
     */
    private static function _setCaInfo($_caInfo){
        //格式化
        $sessioninfo['ca_source']=$_caInfo['ca_source']?$_caInfo['ca_source']:'-';
        $sessioninfo['ca_name']=$_caInfo['ca_name']?$_caInfo['ca_name']:'-';
        $sessioninfo['ca_kw']=$_caInfo['ca_kw']?$_caInfo['ca_kw']:'-';
        $sessioninfo['ca_id']=$_caInfo['ca_id']?$_caInfo['ca_id']:'-';
        if(!empty($sessioninfo))
        {
            //cookie兼容旧版caname
            if($sessioninfo['ca_name']=='-') {//empty
                $canameCookie=$_COOKIE[COOKIE_NAME_CA_NAME]?$_COOKIE[COOKIE_NAME_CA_NAME]:'-';
            }
            //设置session存储
            if($sessioninfo['ca_source']=='-'&&$sessioninfo['ca_name']=='-'){
                return false;
            }else{
                SessionNamespace::setValue(self::CA_INFO_SESSION_KEY, json_encode($sessioninfo));
            }
        }else{
            return false;
        }
        return true;
    }

    /**
     * get渠道四元组 第二版
     * 参见jira MSC-4560
     * @return querystring
     */
    private static function _getOrganicInfoV2(){
        //***********storage************//
        $ca_infoStr=SessionNamespace::getValue(self::CA_INFO_SESSION_KEY);
        if($ca_infoStr!=null){
            $ca_info=(array)json_decode($ca_infoStr);
        }
        //session得到四元组
        $caInfo_sn=array();
        $caInfo_sn['ca_name']   = $ca_info['ca_name']&&$ca_info['ca_name']!='-'?$ca_info['ca_name']:'-';
        $caInfo_sn['ca_source'] = $ca_info['ca_name']&&$ca_info['ca_source']!='-'?$ca_info['ca_source']:'-';
        $caInfo_sn['ca_id']     = $ca_info['ca_name']&&$ca_info['ca_id']!='-'?$ca_info['ca_id']:'-';
        $caInfo_sn['ca_kw']     = $ca_info['ca_name']&&$ca_info['ca_kw']!='-'?$ca_info['ca_kw']:'-';

        //************request***************//
        $caInfo_req=array();
        $caInfo_req['ca_name']  =self::_getRequestCaInfoV2("ca_name");
        $caInfo_req['ca_source']=self::_getRequestCaInfoV2("ca_source");
        $caInfo_req['ca_id']    =self::_getRequestCaInfoV2("ca_id");
        $caInfo_req['ca_kw']    =self::_getRequestCaInfoV2("ca_kw");

        //***********set****************/
        $caInfo_set=array();
        if($caInfo_req['ca_source']!='-'){
            if($caInfo_req['ca_name']!='-'){
                $caInfo_set=array(
                    'ca_source'=>$caInfo_req['ca_source'],
                    'ca_name'  =>$caInfo_req['ca_name'],
                    'ca_kw'    =>$caInfo_req['ca_kw'],
                    'ca_id'    =>$caInfo_req['ca_id'],
                );
            }else{
                $caInfo_set=array(
                    'ca_source'=>$caInfo_req['ca_source'],
                    'ca_name'  =>'-',
                    'ca_kw'    =>$caInfo_req['ca_kw'],
                    'ca_id'    =>$caInfo_req['ca_id'],
                );

            }
        }else{
            if($caInfo_req['ca_name']!='-'){
                $caInfo_set=array(
                    'ca_name'  =>$caInfo_req['ca_name'],
                    'ca_source'=>'-',
                    'ca_kw'    =>$caInfo_req['ca_kw'],
                    'ca_id'    =>$caInfo_req['ca_id'],
                );
            }else{
                //	$caInfo_set=array(
                // 					'ca_source'=>$caInfo_sn['ca_source'],
                // 					'ca_name'  =>$caInfo_sn['ca_name'],
                //);
            }
        }
        if(!empty($caInfo_set)){
            $caInfo_sn=array_merge($caInfo_sn,$caInfo_set);
        }
        if($caInfo_sn!==$ca_info) {
            SessionNamespace::setValue(self::CA_INFO_SESSION_KEY, json_encode($caInfo_sn));
        }
        return urlencode(http_build_query($caInfo_sn));
    }

    /**
     * 分析refer 获取来自搜索引擎的关键词
     * @return string 搜索引擎关键词
     */
    private static function _getSearchWord()
    {
        $refer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        if(strstr( $refer, 'baidu.com')) {
            //百度
            preg_match("|baidu.+wo?r?d=([^\\&]*)|is", $refer, $tmp);
            $keyword = urldecode($tmp[1]);
        } else if (strstr( $refer, 'google.com') or strstr( $refer, 'google.cn')) {
            //谷歌
            preg_match( "|google.+q=([^\\&]*)|is", $refer, $tmp );
            $keyword = urldecode($tmp[1]);
        }else if (strstr( $refer, 'so.com')) {
            //360搜索
            preg_match( "|so.+q=([^\\&]*)|is", $refer, $tmp );
            $keyword = urldecode($tmp[1]);
        }elseif(strstr( $refer, 'sogou.com')){
            //搜狗
            preg_match( "|sogou.com.+query=([^\\&]*)|is", $refer, $tmp );
            $keyword = urldecode($tmp[1]);
        }elseif(strstr( $refer, 'soso.com')){
            //搜搜
            preg_match( "|soso.com.+w=([^\\&]*)|is", $refer, $tmp );
            $keyword = urldecode($tmp[1]);
        }else {
            $keyword ='-';
        }
        return $keyword;
    }

    /**
     *负责返回request参数，格式保证至少格式成"-"
     * @return string|'-'
     */
    private static function _getRequestCaInfoV2($key){
        switch ($key) {
            case 'ca_name':
                $ca_name = !empty($_GET['ca_name']) ? $_GET['ca_name']
                    : (!empty($_GET['from']) ? $_GET['from'] : $_GET['channel']);
                return $ca_name?$ca_name:'-';
            case 'ca_source':
                $urlInfo=parse_url(HttpNamespace::getReferer());
                if(!isset($urlInfo['host'])){
                    $urlInfo['host']='-';
                }
                if(stripos($urlInfo['host'],'.ganji.c')!==false||stripos($urlInfo['host'],'ganji.c')!==false){
                    //unset($urlInfo['host']);//内部,不设置ca_source
                    $urlInfo['host']='-';
                }
                return HttpNamespace::getREQUEST('ca_source',$urlInfo['host']);
            case 'ca_kw':
                return self::_getSearchWord();
            default:
                return HttpNamespace::getREQUEST($key,'-');
        }
    }
    /**
     * GET有，返回，没有就从cookie中返回
     * @return string|Ambigous <string, unknown>
     */
    private static function _getCaInfo(){
        //优先取GET
        //ca_name
        $ca_name=self::_getCaName();//get oldCookie
        $ca_kw=$_GET['ca_kw']?$_GET['ca_kw']:'';
        $ca_id=$_GET['ca_id']?$_GET['ca_id']:'';
        $ca_source=$_GET['ca_source']?$_GET['ca_source']:'';
        //ca_name
        if(empty($ca_name)&&empty($ca_source)){//ca_cource被赋值，caname跟随src
            //只有caname为空，才触发get from storage
            $ca_infoStr=SessionNamespace::getValue(self::CA_INFO_SESSION_KEY);
            if($ca_infoStr!=null){
                $ca_info=(array)json_decode($ca_infoStr);
            }
            //session得到四元组
            $ca_name  =$ca_info['ca_name']!='-'?  $ca_info['ca_name']:$ca_name;
            //ca_source
            $ca_id    =$ca_info['ca_id']!='-'?    $ca_info['ca_id']:$ca_id;
            $ca_kw    =$ca_info['ca_kw']!='-'?    $ca_info['ca_kw']:$ca_kw;
        }
        //ca_source
        if(empty($ca_source)){
            $ca_source=$ca_info['ca_source']!='-'?$ca_info['ca_source']:$ca_source;
            if(empty($ca_source)){//从referer得到
                $refereUrl=				HttpNamespace::getReferer();
                $refereInfo=parse_url($refereUrl);
                isset($refereInfo['host'])?$ca_source=$refereInfo['host']:'';
                if(stripos($ca_source,'ganji.c')!=false){
                    $ca_source='';//referer是从ganji来，情况
                }
            }
        }
        //如果最新！=sotrage， 使用最新值覆盖 storage
        $ca_Info_T=array(
            'ca_source'=>$ca_source,'ca_name'=>$ca_name,
            'ca_kw'=>$ca_kw,'ca_id'=>$ca_id,
        );
        if($ca_Info_T!=$ca_info){
            self::_setCaInfo($ca_Info_T);
        }
        return $ca_Info_T;
    }


    protected  static function _getIfid($ifid='')
    {
        if (empty($ifid)) {
            $ifid = !empty($_GET['ifid']) ? $_GET['ifid']:$_POST['ifid'];
        }
        return $ifid;//ifid 不做记录
    }

    /**
     * @deprecated self::_setCaInfo
     * @param string $ca_name
     * @param string $ca_source
     * @param string $ca_kw
     * @param string $ca_id
     */
    private  static function _setCaName($ca_name='', $ca_source='', $ca_kw='', $ca_id='')
    {
        if(!empty($ca_name))
        {
            $timeStamp = time();
            //             setrawcookie(COOKIE_NAME_CA_NAME, urlencode($ca_name), $timeStamp + COOKIE_USER_PERSISTENCE, COOKIE_PATH,WAP_COOKIE_DOMIAN);
            setrawcookie(COOKIE_NAME_CA_NAME, urlencode($ca_name), 0, COOKIE_PATH,WAP_COOKIE_DOMIAN);
        }
    }
    /**
     * 获取渠道来源用户id
     * @param str $ca_name
     * @param unknown_type $ca_source
     * @param unknown_type $ca_kw
     * @param unknown_type $ca_id
     * @return string|Ambigous <string, unknown>
     */
    private static function _getCaName($ca_name='', $ca_source='', $ca_kw='', $ca_id='')
    {
        if (empty($ca_name)) {
            $ca_name = !empty($_GET['ca_name']) ? $_GET['ca_name']
                : (!empty($_GET['from']) ? $_GET['from'] : $_GET['channel']);
        }
        $ca_nameCookie_T = $_COOKIE[COOKIE_NAME_CA_NAME];//urlen

        $ca_nameReq_T=urlencode($ca_name);//urlen

        //req 包含ca_name
        if(!empty($ca_nameReq_T))//用最新值 覆盖cookie 返回值
        {
            //不再使用cookie记录caname
            // 			if($ca_nameCookie_T!=$ca_nameReq_T)
            // 			{
            // 				self::_setCaName($ca_nameReq_T);
            // 			}
            return $ca_nameReq_T;
        }
        //不包含
        return $ca_nameCookie_T?$ca_nameCookie_T:'';
    }

    public static  function  writeToLocalV2($utmUrl)
    {
        require_once(dirname(__FILE__)."/WapLog.php");
        WapLog::log("waplogv2", $utmUrl);
    }


    public static  function  preTrackPageViewDz()
    {
        $cookie = $_COOKIE[COOKIE_NAME_DZ];
        $userAgent = $_SERVER["HTTP_USER_AGENT"] ? $_SERVER["HTTP_USER_AGENT"] : "-";
        $visitorId =self:: getVisitorId($userAgent, $cookie);
        $timeStamp = time();
        setrawcookie(COOKIE_NAME_DZ, $visitorId, $timeStamp + COOKIE_USER_PERSISTENCE, COOKIE_PATH,WAP_COOKIE_DOMIAN);

        $ip =self:: getIP($_SERVER["REMOTE_ADDR"]);
        //$guuid = urlencode($_COOKIE["ssId"]);
        $user_id = urlencode($_COOKIE["userId"]);
        $uc_id = urlencode($_COOKIE["ucId"]);
        $refer = urlencode($_SERVER ["HTTP_REFERER"]);
        $url = urlencode(self::curPageURL());
        $ua = urlencode($_SERVER["HTTP_USER_AGENT"]);

        $utmUrlArr = array(
            "ip" => $ip ,
            "guuid" => $visitorId,
            "user_id" => $user_id,
            "uc_id" => $uc_id,
            "refer" => $refer,
            "url" => $url,
            "user_agent" => $ua,
        );

        return $utmUrlArr;
    }


    //gjch is in setGoogleAnalytics_Gjch(WapPostDetailPage) or more
    public static  function  InvokeWapLogV2($arr=array())
    {
        //TODO absolute path
        $utmGifLocation = "/ga_v2.php";
        $utmUrlArr = array_merge(self::preTrackPageViewV2(), $arr);
        //print_r($utmUrlArr);
        $utmUrl = $utmGifLocation . "?" . http_build_query($utmUrlArr);
        self::writeToLocalV2($utmUrl);
    }

    public static  function  InvokeWapLogVar($version, $cookie_name)
    {
        $utmip =self:: getIP($_SERVER["REMOTE_ADDR"]);
        $utmp = urlencode(self::curPageURL());
        $utmr = urlencode($_SERVER ["HTTP_REFERER"]);
        $utmua = urlencode($_SERVER["HTTP_USER_AGENT"]);

        $cookie = $_COOKIE[$cookie_name];
        $userAgent = $_SERVER["HTTP_USER_AGENT"] ? $_SERVER["HTTP_USER_AGENT"] : "-";
        $visitorId =self:: getVisitorId($userAgent, $cookie);
        $timeStamp = time();
        setrawcookie($cookie_name, $visitorId, $timeStamp + COOKIE_USER_PERSISTENCE, COOKIE_PATH,WAP_COOKIE_DOMIAN);

        $utmUrlArr = array(
            "utmip" => $utmip, //ip
            "utmvisitorid" => $visitorId,  // session id
            "utmp" => $utmp, // current request uri
            "utmr" => $utmr, // refer
            "utmua" => $utmua, // user agent
        );

        $utmUrl = "/ga_v2.php?" . http_build_query($utmUrlArr);
        require_once(dirname(__FILE__)."/WapLog.php");
        WapLog::log($version, $utmUrl);
    }

    public static  function  InvokeWapLogTouch()
    {
        self::  InvokeWapLogVar("waplogtouch", COOKIE_NAME_TOUCH);
    }

    public static  function  InvokeWapLogHtml5()
    {
        self:: InvokeWapLogVar("waploghtml5", COOKIE_NAME_HTML5);
    }

    public static  function  TempWapLog()
    {
        $host = $_SERVER["HTTP_HOST"];
        if($host == "3g.ganji.cn")
            self::  InvokeWapLogTouch();
        if($host == "m.ganji.cn")
            self:: InvokeWapLogHtml5();
    }

    public static  function  InvokeWapLogBoth($path, $refer)
    {
        /*
         $utmGifLocation = "/ga.php";
        $utmUrlArr = preTrackPageViewV1();
        $utmUrlArr["utmp"] = urlencode($path);
        $utmUrlArr["utmr"] = urlencode($refer);
        //print_r($utmUrlArr);
        $utmUrl = $utmGifLocation . "?" . http_build_query($utmUrlArr);
        writeToLocalV1($utmUrl);
        */
        $utmGifLocation = "/ga_v2.php";
        $utmUrlArr =self:: preTrackPageViewV2();
        $utmUrlArr["utmp"] = urlencode($path);
        $utmUrlArr["utmr"] = urlencode($refer);
        $utmUrl = $utmGifLocation . "?" . http_build_query($utmUrlArr);
        self:: writeToLocalV2($utmUrl);
    }


    public static  function  writeToLocalDz($page_type, $channel_name='-', $city_name='-', $parameters = array())
    {
        $utmUrlArr =self:: preTrackPageViewDz();
        if(empty($page_type))
            $page_type = '-';
        if(empty($channel_name))
            $channel_name = '-';
        if(empty($city_name))
            $city_name = '-';

        $parameters_str = '';
        if($parameters){
            foreach($parameters as $key => $value){
                $parameters_str .= $key . ":" . $value . "|";
            }
            $parameters_str = rtrim($parameters_str, "|");
        } else {
            $parameters_str = '-';
        }

        //validate page_type
        $utmUrlArr['page_type'] = $page_type;
        $utmUrlArr['channel_name'] = $channel_name;
        $utmUrlArr['city_name'] = $city_name;
        $utmUrlArr['parameters'] = $parameters_str;
        $utmUrl =  http_build_query($utmUrlArr);
        require_once(dirname(__FILE__)."/WapLog.php");
        WapLog::log("waplogdz", $utmUrl);
    }


    public static  function  writeToLocalAds($utmUrl)
    {
        require_once(dirname(__FILE__)."/WapLog.php");
        return WapLog::log("waplogads", $utmUrl);
    }


    public static  function  writeToLocalAclk($utmUrl)
    {
        require_once(dirname(__FILE__)."/WapLog.php");
        WapLog::log("waplogaclk", $utmUrl);
    }


    public static  function  writeToLocalJiuyou($utmUrl)
    {
        require_once(dirname(__FILE__)."/WapLog.php");
        WapLog::log("waplogjiuyou", $utmUrl);
    }


    public static  function  writeToLocalWURFL($utmUrl)
    {
        require_once(dirname(__FILE__)."/WapLog.php");
        WapLog::log("waplogUA_WURFL", $utmUrl);
    }
    public static function writeToLocalDNS($utmUrl)
    {
        require_once(dirname(__FILE__)."/WapLog.php");
        WapLog::log("waplogDNS", $utmUrl);
    }
}