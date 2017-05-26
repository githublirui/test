<?PHP
require_once dirname(__FILE__) . '/../../config/config.inc.php';
require_once GANJI_CONF . '/GlobalConfig.class.php';
class MailNamespace {
    public static $KEY_MS_GETPASSWORD  		= 'MainSite-GetPassword';
    public static $KEY_MS_EMAIL_COMFIRM     = 'MainSite-EmailConfirm';
    //public static $KEY_MS_EmailApprove 		= 'MainSite-EmailApprove';///> 邮箱认证 add by zhengyifeng  date 2011.5.30
    public static $KEY_MS_MAINSITE_EMAIL 	= 'MainSite-Email';		  ///> 主站 非重要邮件
    public static $KEY_MS_APPMONITOR_ALERT	= 'AppMonitor-Alert'; 	  ///> 程序日志及性能监控报警
    public static $KEY_MS_TUIGUANG_NOTICE 	= 'TuiGuang-Notice';      ///> 推广后台 未激活用户更新资料
    public static $KEY_MS_CRM_NOTICE		= 'CRM-Notice'; 		  ///> 房产 开户出错通知 帐户 开通或拒绝通知
    public static $KEY_MS_MAINSITE_GROUPON  = 'MainSite-Groupon';	  ///> 团购注册，团购找回密码
    public static $KEY_MS_VIP_SENDINVITED   = 'UC-Interview';	  ///> 用户中心，发送面试邀请
    public static $KEY_JOB_SENDFINDJOB      = 'JOB-SENDFINDJOB';	  ///> 求职招聘--给企业用户发送简历
    public static $KEY_MS_SHORTRENT         = 'MainSite-ShortRent';   ///> 赶集短租业务邮件服务
    public static $KEY_MS_HOUSE_INVESTIGATE = 'house-diaocha'; // 房产，问卷调查

    protected static $HTTP_TIMEOUT = 3;
    
    /**
     * @brief 发送邮件
     * @param String $addr 接收人的Email地址
     * @param String $title 标题
     * @param String $content 邮件正文
     * @param String $key 业务KEY，见本类的常量
     * @return boolean True 成功，False 失败
     */
    public static function sendMail($addr, $title, $content, $key) {
        $fields = array(
                        'opt'=>urlencode('send'),
                        'uniqueId'=>urlencode(''),
                        'serviceId'=>urlencode($key),
                        'emails'=>urlencode($addr),
                        'mailFromAdress'=>'',
                        'mailFromName '=>'',
                        'title'=>urlencode($title),
                        'content'=>urlencode($content)
                        );
         foreach( GlobalConfig::$MAIL_SERVER as $url) {
            if( self::_httpSend( $url , $fields )) return TRUE;
         }
         return FALSE;
    }    
    
    /**
     * @brief 通过CURL进行邮件发送，使用GlobalConfig中的MAIL服务器URL
     */
    protected static function _httpSend($url, $fields) 
    {   
        $fields_string = '';
        //url-ify the data for the POST
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string,'&');
        
        //open connection
        $ch = curl_init();
        
            //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
        curl_setopt($ch,CURLOPT_TIMEOUT,self::$HTTP_TIMEOUT);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); // ADD BY MAWENLONG 不打印出curl的执行结果
        
        //execute post
        $result = curl_exec($ch);
        
        if( $result === FALSE ) {
            //error occurs , do logging here
            Logger::logError("Mail send failed, url={$url}, content={$fields_string}," . curl_error($ch),
                             "user.mail");
        }

        //close connection
        curl_close($ch);
        
        return $result;
    } 
}
