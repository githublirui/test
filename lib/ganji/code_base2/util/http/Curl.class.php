<?php
/**
 * 此模块是负责php访问其他url使用的
 * @package              code_base2.http
 * @author               yangyu <doubleycn@gamil.com>
 * @file                 $RCSfile: Curl.class.php,v $
 * @version              $Revision: 1.25 $
 * @date                 2011-03-25
 * @modifiedby           $Author: yangyu $
 * @lastmodified         $Date: 2009/04/27 02:29:15 $
 * @copyright            Copyright (c) 2011, eYou.com
 */
class Curl{

    public $mCookFile            = null;
    public $mIsOutputHeader      = false;//是否输出head
    public $mIsNoBody            = false; //是否不输出body
    public $mIsLocation          = true;//是否允许跳转
    public $mVerbose             = false; //详细报告
    public $mReturntransfer      = 1;    //
    public $mTimeout             = 3;    //设置curl允许执行的最长秒数
    public $cTimeout             = 2; //设置curl等待连接的超时,php5.2.3可用
    public $mReturnHtml          = null; //返回的html内容
    public $mInfo                      = null; //curl_getinfo返回值
    public $mProxy               = null; //代理ip
    public $mMethod 		        = "post";
    private $mSslCert   = null; //ssl 私钥证书string
    private $mSslCertPasswd = null;//ssl 私钥证书密码
    private $mSslKey    = null; //ssl 私钥证书string
    private $mSslKeyPasswd = null;//ssl 私钥证书密码
    private $mSslCaFile = null;
    private $mSslCheck  = false;
    private $logPath    = "/ganji/ganji_log";
    public $msg      = array();
    private $errorCode = 0; // curl最近一次错误码

    /** {{{http 头
     *$this_header = array(
     *  "MIME-Version: 1.0",
     *  "Content-type: text/html; charset=iso-8859-1",
     *  "Content-transfer-encoding: text"
     *  );
      */
    var $mHttpHeader    = null;
    //}}}
    
    var $mReferer       = null;

    
    /**
     * @brief 构造函数
     *
     */
    function __construct(){
    }
    
    /**
     * 登录网站，并会创建cookie，给后续get，post等操作提供支持
     * @brief curl方式登陆网站
     * 
     * @param $url string 登陆的url
     * @param $request array 要提交的参数，默认没有任何参数
     * @param $method string 指出http请求是post方式，还是get方式
     * @returns  成功返回页面html，失败返回false
     * @see Curl::get
     * @see Curl::post
     */
    public function login($url,$request=null, $method = 'post'){
        $this->enabledCookie();
        $ch = curl_init();
		if($method=="post"){//支持get方式登录
            $this->mReturnHtml = $this->post($url,$request);
		}else{
			if(false===strpos($url,"?")){
				$url .= "?".$this->dataEncode($request);
			}else{
				$url .= "&".$this->dataEncode($request);
			}
            $this->mReturnHtml = $this->get($url);
		}
        return $this->mReturnHtml;
    }
    //@{
    /** 设置属性 */
    
    /**
     * @brief 设置是否输出head,默认不输出
     *
     * @param $bool boolean true 输出，false：不输出
     */
    public function setIsOutputHeader($bool){
        if(is_bool($bool)){
            $this->mIsOutputHeader = $bool;
            return true;
        }
        return false;
    }
    
    /**
     * @brief 设置是否输出body,默认输出
     *
     * @param $bool boolean true 不输出，false：输出
     * @returns 成功:true   失败:false
     */
    public function setIsNoBody($bool){
        if(is_bool($bool)){
            $this->mIsNoBody = $bool;
            return true;
        }
        return false;
    }
    
    /**
     * @brief 设置过期时间，默认3秒
     *
     * @param $second int 过期的秒数
     * @returns boolean 成功:true, 失败:false
     */
    public function setTimeout($second){
        if(is_int($second)){
            $this->mTimeout = $second;
            return true;
        }
        return false;
    }

     /**
     * @brief 设置等待连接过期时间，默认2秒
     *
     * @param $second int 过期的秒数
     * @returns boolean 成功:true, 失败:false
     */
    public function setConnectTimeout($second){
        if(is_int($second)){
             $this->cTimeout = $second;
             return true;
        }
        return false;

    }
    
    /**
     * @brief 设置http referer
     *
     * @param $referer string referer地址
     * @returns boolean 成功:true, 失败:false
     */
    public function setReferer($referer){
        if(is_string($referer)){
            $this->mReferer = $referer;
            return true;
        }
        return false;
    }
    
    /**
     * @brief 设置http请求的时候是否显示详细报告
     *
     * @param $verbose true:显示， false：不显示
     * @returns boolean 成功:true, 失败:false
     */
    public function setVerbose($verbose){
        if(is_bool($verbose)){
            $this->mVerbose = $verbose;
            return true;
        }
        return false;
    }
    //@}
    //@{
    /** HTTP 方法数据请求 */
    
    /** 
     * @brief 向某个url 请求数据 post($url,$post=null)
     * @param  string   要请求的url 地址
     * @param  array    要post的数据
     * @param  boolean  是否要上传文件,true的时候，不进行编码
     * @return 成功返回请求url的html，失败返回false
     */
    function post($url,$post= null ,$updatefile = false){
        if(false == $updatefile){
            $post = $this->dataEncode($post);
        }
        if($post == false ){
            return false;
        }
        $ch = curl_init();   
        curl_setopt($ch, CURLOPT_URL, $url);   
        curl_setopt($ch, CURLOPT_HEADER, $this->mIsOutputHeader);   
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $this->mIsLocation);
        curl_setopt($ch, CURLOPT_VERBOSE, $this->mVerbose);//报告每一个细节
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->mCookFile); //设定返回的数据是否自动显示
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->cTimeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->mTimeout);   
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->mCookFile);   
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; (R1 1.5))');
        if(is_array($this->mHttpHeader)) curl_setopt($ch, CURLOPT_HTTPHEADER, $this->mHttpHeader);
        //{{{ ssl
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,$this->mSslCheck);
        /*
        if(!empty($this->mSslCert)) curl_setopt($ch,CURLOPT_SSLCERT,$this->mSslCert);
        if(!empty($this->mSslCertPasswd)) curl_setopt($ch,CURLOPT_SSLCERTPASSWD,$this->mSslCertPasswd);
        if(!empty($this->mSslKey)) curl_setopt($ch,CURLOPT_SSLKEY,$this->mSslKey);
        if(!empty($this->mSslKeyPasswd)) curl_setopt($ch,CURLOPT_SSLKEYPASSWD,$this->mSslKeyPasswd);
        if(!empty($this->mSslCaFile)) curl_setopt($ch,CURLOPT_CAINFO,$this->mSslCaFile);
        */
        
        $msg = "请求url:".$url;
        $html = curl_exec($ch);
        if ($this->errorCode = curl_errno($ch)) {
            // @codeCoverageIgnoreStart
            $this->setMsg(curl_error($ch));
            $msg .= "失败.msg:" . curl_error($ch);
            $this->log($msg);
            return false;
            // @codeCoverageIgnoreEnd
        }
        curl_close($ch);    
        $msg .= "成功";
        $this->log($msg);
        return $html;
    }
    
    /**
     * @brief 向某个url GET请求数据
     *
     * @param $url string   要请求的url 地址
     *
     * @returns  成功返回url的html 
     */
    public function get($url){
        $ch = curl_init();   
        curl_setopt($ch, CURLOPT_URL, $url);   
        curl_setopt($ch, CURLOPT_HEADER, $this->mIsOutputHeader);   
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->mCookFile);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, $this->mReturntransfer);   
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->mTimeout);   
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->mCookFile);   
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $this->mIsLocation);
        curl_setopt($ch, CURLOPT_NOBODY, $this->mIsNoBody);
        curl_setopt($ch, CURLOPT_VERBOSE, $this->mVerbose);//报告每一个细节
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; (R1 1.5))');
        //{{{ ssl
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,$this->mSslCheck);
        //}}}
        /*
        if(!empty($this->mSslCert)) curl_setopt($ch,CURLOPT_SSLCERT,$this->mSslCert);
        if(!empty($this->mSslCertPasswd)) curl_setopt($ch,CURLOPT_SSLCERTPASSWD,$this->mSslCertPasswd);
        if(!empty($this->mSslKey)) curl_setopt($ch,CURLOPT_SSLKEY,$this->mSslKey);
        if(!empty($this->mSslKeyPasswd)) curl_setopt($ch,CURLOPT_SSLKEYPASSWD,$this->mSslKeyPasswd);
        if(!empty($this->mSslCaFile)) curl_setopt($ch,CURLOPT_CAINFO,$this->mSslCaFile);
        */
        
        // @codeCoverageIgnoreStart
        if($this->mProxy){
            curl_setopt($ch, CURLOPT_PROXY,$this->mProxy);
            curl_setopt($ch, CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_0);
        }
        // @codeCoverageIgnoreEnd
        if(null != $this->mReferer) curl_setopt($ch, CURLOPT_REFERER, $this->mReferer);
        if(is_array($this->mHttpHeader)) curl_setopt($ch, CURLOPT_HTTPHEADER, $this->mHttpHeader);
        $msg = "请求url:".$url;
        $html = curl_exec($ch);
        if ($this->errorCode = curl_errno($ch)) {
            $this->setMsg(curl_error($ch));
            // @codeCoverageIgnoreStart
            return false;
            // @codeCoverageIgnoreEnd
        }
        $this->mInfo = curl_getinfo($ch);
        $msg .= "成功";
        $this->log($msg);
        curl_close($ch);    
        return $html;
    }
    
    /**
     * @brief 下载url
     *
     * @param $url 请求的url
     * @param $filename 要把文件存储的位置
     *
     * @returns  成功返回下载的文件名，失败返回false 
     */
    public function download($url,$filename = ''){
        if ( empty($url) ){
            return false;
        }
        if( empty($filename) ){
            $url_info = parse_url($url);
            $filename = "/tmp/" . basename($url_info['path']);
        }
        $this->log("begin download {$url}");
        $fp = @fopen($filename, "w");
        if($fp){
            $this->log($filename);
            $ch = curl_init();   
            curl_setopt($ch, CURLOPT_URL, $url);   
            curl_setopt($ch, CURLOPT_HEADER, $this->mIsOutputHeader);   
            curl_setopt($ch, CURLOPT_COOKIEJAR, $this->mCookFile); //设定返回的数据是否自动显示
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, $this->mReturntransfer);   
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->mTimeout);   
            curl_setopt($ch, CURLOPT_COOKIEFILE, $this->mCookFile);   
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $this->mIsLocation);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; (R1 1.5))');
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_exec($ch);
            if ($this->errorCode = curl_errno($ch)) {
                // @codeCoverageIgnoreStart
                $msg = curl_error($ch);
                $this->log($msg);
                return false;
                // @codeCoverageIgnoreEnd
            }
            curl_close($ch);

            fclose($fp);
            return $filename;
        } else {
            $this->log("{$filename} can't open");
        }
        return false;
    } 
    //@}
    
    /**
     * @brief 将array形式的参数转换成url query需要的格式
     *
     * @param $data
     *
     * @returns   
     */
    public function dataEncode($data){
        if(is_array($data)) return http_build_query($data);
        else{
            return $data;
        }
    }
    
    /**
     * @brief 读取http Code信息
     *
     * @param $url  string  要请求的地址
     *
     * @returns  成功返回http返回的code，失败返回false 
     */
    public function getHttpCode($url){
        $ch = curl_init();   
        curl_setopt($ch, CURLOPT_URL, $url);   
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->mTimeout);   
        curl_setopt($ch, CURLOPT_NOBODY, true);
        $html = curl_exec($ch);   
        if ($this->errorCode = curl_errno($ch)) {
            // @codeCoverageIgnoreStart
            return false;
            // @codeCoverageIgnoreEnd
        }
        $info = curl_getinfo($ch);
        curl_close($ch);    
        return (int)$info['http_code'];
    }
    /** 记录log log($msg)
      * @param  string  错误消息
      * @return void
     */
    function log($msg,$logfile = null){
        return;
        // 如果目录不存在就不记录log了
        if (false == file_exists($this->logPath)) {
            return;
        }
        $date = date("Y-m-d H:i:s");
        $filedate = date("Y_m_d");
        $msg = "[".$date."]:".$msg."\n";
 //       $msg = iconv('UTF-8', 'GBK', $msg);
 //       echo $msg;
        if(empty($logfile)) $logfile = $this->logPath . "/curl_".$filedate.".log";
        if (false === file_exists($logfile)) {
            touch($logfile);
            chmod($logfile, 0666);//rw-rw-rw-
        }
        @error_log($msg, 3, $logfile);
    }
    /* {{{ protected setMsg */
    /**
     * @brief 设定返回消息
     *
     * @param $msg string 要设定的消息
     */
    protected function setMsg($msg){
        $this->msg[] = $msg;
    }//}}}
    /* {{{ getMsg */
    /**
     * @brief 获取错误消息
     * @return string 返回最后一次错误消息
     */
    public function getMsg(){
        if(!empty($this->msg))
            return $this->msg[ count($this->msg) - 1];
        return '';
    }//}}}

    /**
    * @brief: 获取curl最近一次错误码
    * @return int 错误码
    */
    public function getErrorCode(){/*{{{*/
        return $this->errorCode;
    }/*}}}*/


    
    /**
     * @brief 开启cookie功能
     *
     * @returns   
     */
    protected function enabledCookie(){
        $this->mCookFile = tempnam('/tmp','cookie');
        if(file_exists($this->mCookFile)){//文件存在
            $fp = @fopen($this->mCookFile,"r+");
            $ok = @ftruncate($fp,'0');
            @fclose($fp);
            if($ok == false){
                // @codeCoverageIgnoreStart
                return false;
                // @codeCoverageIgnoreEnd
            }
        }
        return true;
    }
}//end class
