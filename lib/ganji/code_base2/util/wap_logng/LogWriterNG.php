<?php
//ini_set('display_errors', 1);
//error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

class LogWriterNG 
{
    private $_webHandle;


    public function LogWriterNG($webHandle)
    {
        $this->_webHandle=$webHandle;
    }


    public function run($content)
    {
        require_once 'FileNameNG.php';
        $filename=	FileNameNG::getFileName($this->_webHandle);
        $status=  $this->writeTextToFileSystem($content, $filename);
    }


    /**
     * 将日志写入文件系统
     * @param str $content
     * @param str $filename
     */
    private function writeTextToFileSystem($content,$filename){
        $status=false;
        $fileDir=dirname($filename);
        if (!is_dir($fileDir)){
            mkdir($fileDir,0777,true);
            //TODO check return value
        }
        if(false == ($handle = fopen($filename, 'a'))){
            $status = false;
        }else{
            $status = (false === fwrite($handle, $content) ) ? false: true;
            fclose($handle);
        }
        return $status;
    }
    /**
     * 
     * @param string $logstr
     * @param string $category
     */
    public static function WriteTextToScribe($logstr){
    	$category="rta.mobile.wap.gav3";
        if(!class_exists('Logger')){
            require_once CODE_BASE2.'/util/log/Logger.class.php';
        }   
        if(!class_exists('HttpNamespace')){
            require_once CODE_BASE2.'/util/http/HttpNamespace.class.php';
        }   
        $referer = HttpNamespace::getReferer();
        $referer = $referer?$referer:"-";
        $ua      = $_SERVER['HTTP_USER_AGENT'];
        $ua      = $ua?$ua:"-";
        $ip      = HttpNamespace::getIp(false);
        $ip      = $ip?$ip:'-';
        $serverIp= $_SERVER['SERVER_ADDR'];
        $serverIp= $serverIp?$serverIp:'-';

        $logstr=str_replace("\n", '',$logstr);
        $logstr=urlencode($logstr);
        //build
        $head = $ip.' analytics.ganji.com '.$serverIp.' ';
        $time = date('j/M/Y:H:i:s');
        $head = $head . "[".$time . ' +0800] "GET/wapp.gif?logstr=';
        $end = ' HTTP/1.1" 200 35 "' . $referer . '" "'.$ua.'" 0.000'."\n";
        $message = $head . $logstr . $end;
        if (class_exists('Logger')) {
        	Logger::logDirect($category,$message);
    		Logger::logWarn($message,'gav3');
        }   
    }
}
