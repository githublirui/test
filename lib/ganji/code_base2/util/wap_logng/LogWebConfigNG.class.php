<?php
/**
 * wap日志各个字段配置意义
 *
 * @author    wangjian
 * @touch     2012-11-28
 * @category  wap
 * @package   LogWebConfigNG.class.php 
 * @version   0.1.0
 * @copyright Copyright (c) 2005-2012 GanJi Inc. (http://www.ganji.com)
 */
class LogWebConfigNG
{
    //fields to log
    public static $LogFields=array
    (
         'waplog'=>array (
    // "waplog V1.0"
    //'0'=>'{{date('y-m-d h:i:s')}}'// added by WapLog
             '1'=>'utmhn',     // domain name
             '2'=>'utmr',      // refer
             '3'=>'utmp',      // current request uri, not urlencoded
             '4'=>'utmvid',    // session id
             '5'=>'utmip',     // gjch
             '6'=>'utmua',     // user agent
             '7'=>'cate',      // category
             '8'=>'majcate',   // major category
             '9'=>'mincate',   // min category
             '10'=>'channel',  // channel
             '11'=>'appid',    // application id
    ),
         'waplogv2'=>array (
    //'0'=>'{{date('y-m-d h:i:s')}}'// added by WapLog
             '1' => 'utmip',          // ip
             '2' => 'utmisCookieble', // is support cookie
             '3' => 'utmvisitorid',   // uv
             '4' => 'utmsid',         // session id
             '5' => 'utmgjch',        // gjch
             '6' => 'utmorganicinfo', // channel tuple
             '7' => 'utmifid',        // inner id
             '8' => 'utmgjver',       // version
             '9' => 'utmgjeval',      // for ab test
             '10' => 'utmp',          // current request uri(not path), not urlencoded
             '11' => 'utmr',          // refer
             '12' => 'utmua',         // user agent
             '13' => 'utmcity',       // city domain
             '14' => 'utmuuid',       // uuid
             '15' => 'parameters',    // os/browser parameters
             '16' => 'utmgjuser_id',     //user_id
             '17' => 'utmgjgc',       //gjch_new @same as utmgjch
             '18' => 'utmpvid',       //pv id
    ),
         'waplogng'=>array (
    //'0'=>'{{date('y-m-d h:i:s')}}'// added by WapLog
                 '1' => 'utmip',          // ip
                 '2' => 'utmisCookieble', // is support cookie
                 '3' => 'utmvisitorid',   // uv
                 '4' => 'utmsid',         // session id
                 '5' => 'utmgjch',        // gjch
                 '6' => 'utmorganicinfo', // channel tuple
                 '7' => 'utmifid',        // inner id
                 '8' => 'utmgjver',       // version
                 '9' => 'utmgjeval',      // for ab test
                 '10' => 'utmp',          // current request uri, not urlencoded
                 '11' => 'utmr',          // refer
                 '12' => 'utmua',         // user agency
                 '13' => 'utmcity',       // city domain
                 '14' => 'utmuuid',       // uuid
    ),
         'waplogads'=>array (
    //'0'=>'{{date('y-m-d h:i:s')}}'// added by WapLog
             '1' => 'utmip',          // ip
             '2' => 'utmisCookieble', // is support cookie
             '3' => 'utmvisitorid',   // uv
             '4' => 'utmsid',         // session id
             '5' => 'utmgjch',        // gjch
             '6' => 'utmorganicinfo', // channel tuple
             '7' => 'utmifid',        // inner id
             '8' => 'utmgjver',       // version
             '9' => 'utmgjeval',      // for ab test
             '10' => 'utmp',          // current request uri(not path), not urlencoded
             '11' => 'utmr',          // refer
             '12' => 'utmua',         // user agent
             '13' => 'utmcity',       // city domain
             '14' => 'utmuuid',       // uuid
             '15' => 'ads',           // ads
    ),
         'waplogaclk'=>array (
    //'0'=>'{{date('y-m-d h:i:s')}}'// added by WapLog
             '1' => 'click_time',
             '2' => 'click_ip',
             '3' => 'click_user_id',
             '4' => 'click_ua',
             '5' => 'click_url',
             '6' => 'click_refer',

             '7' => 'view_time',    // the time when the ads are created
             '8' => 'ip',           // ipv4
             '9' => 'user_id',      // id of user
             '10' => 'ua',          // user agent
             '11' => 'view_page',   // the page where the ads are located
             '12' => 'ad_id',       // the ad unique id
             '13' => 'ad_version',  // ad version
             '14' => 'ad_position', // ad position
             '15' => 'ad_url',      // url to redirect when clicking ads
             '16' => 'result',      // the click result(ok or not)
    ),
         'waplogjiuyou'=>array (
    //'0'=>'{{date('y-m-d h:i:s')}}'// added by WapLog
             '1' => 'utmip',          // ip
             '2' => 'utmisCookieble', // is support cookie
             '3' => 'utmvisitorid',   // uv
             '4' => 'utmsid',         // session id
             '5' => 'utmgjch',        // gjch
             '6' => 'utmorganicinfo', // channel tuple
             '7' => 'utmifid',        // inner id
             '8' => 'utmgjver',       // version
             '9' => 'utmgjeval',      // for ab test
             '10' => 'utmp',          // current request uri(not path), not urlencoded
             '11' => 'utmr',          // refer
             '12' => 'utmua',         // user agent
             '13' => 'utmcity',       // city domain
             '14' => 'utmuuid',       // uuid
             '15' => 'from',          // from
    ),
             'waplogUA_WURFL'=>array(
    //'0'=>'{{date('y-m-d h:i:s')}}'// added by WapLog
             '1' => 'ua',           
             '2' => 'brand_name',  
             '3' => 'model_name',    
             '4' => 'preferred_markup',          
             '5' => 'resolution_width',        
             '6' => 'resolution_height', 
             '7' => 'is_wireless_device',        
             '8' => 'device_os',     
             '9' => 'device_os_version',    
             '10' => 'mobile_browser',          
             '11' => 'j2me_midp_2_0',         
             '12' => 'j2me_midp_1_0',       
             '13' => 'j2me_cldc_1_0',      
             '14' => 'j2me_cldc_1_1',       
             '15' => 'transcoder_ua_header',      
             '16' => 'nokia_series',
             '17' => 'transcoder_ua_header',    
             '18' => 'nokia_feature_pack',     
             '19' => 'nokia_edition',     
 	     	 '20'=>'domain',//city domain
             '21'=>'iscom',// host is end ganji.com 1 true ,0 is ganji.cn ,-1 neither com or cn
             '22'=>'hostname',//original host 
             '23'=>'ABtest',
     
    ),
             'waplogdz'=>array (
    //'0'=>'{{date('y-m-d h:i:s')}}'// added by WapLog
             '1' => 'ip',
             '2' => 'guuid',
             '3' => 'user_id',
             '4' => 'uc_id',
             '5' => 'page_type',
             '6' => 'channel_name',
             '7' => 'refer',
             '8' => 'url',
             '9' => 'user_agent',
             '10' => 'city_name',
             '11' => 'parameters',
    ),
            'waplogDNS'=>array(
    //'0'=>'{{date('y-m-d h:i:s')}}'// added by WapLog
    		 '1' => 'utmip',          // ip
             '2' => 'ua',  
	     	 '3'=>'domain',//city domain
             '4'=>'iscom',// host is end ganji.com 1 true ,0 is ganji.cn ,-1 neither com or cn
             '5'=>'hostname',//original host 
             '6'=>'ABtest',
             '7'=>'googleUid',
    ),
    );
}
?>
