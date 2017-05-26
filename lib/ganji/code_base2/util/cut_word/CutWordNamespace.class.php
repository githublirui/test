<?php
if( !isset($GLOBALS['THRIFT_ROOT'])) {
    $GLOBALS['THRIFT_ROOT'] = CODE_BASE2 . '/third_part/thrift-0.5.0/';
}
require_once GANJI_CONF . '/MemcacheConfig.class.php';
require_once CODE_BASE2 . '/util/cache/CacheNamespace.class.php';
require_once CODE_BASE2 . '/util/cut_word/TSCutWord.php';

class CutWordNamespace
{
    private static $host = null;
    private static $port = null;
    private static $socket = null;
    private static $transport = null;
    private static $client = null;

    public function __construct(){
    }
    /**
     * 日志记录
     * @param string $msg 错误信息
     */
    public function log($msg){
        if(class_exists('Logger')){
            Logger::logError($msg, 'mainsite_CutWord');
        }
    }
    private function connectInterface(){
        $maxRetryTimes = 3;
        for ($i=0; $i<=$maxRetryTimes; $i++) {
            if (! self::$client instanceof TSCutWordClient) {
                try {
                    $rand = time();
                    if ($rand%10<5) {
                        $index = 0;
                    } else {
                        $index = 1;
                    }
                    self::$socket = new TSocket(SearchConfig::$CUT_WORD[$index]['host'],
                        SearchConfig::$CUT_WORD[$index]['port']);
                    self::$socket->setSendTimeout(10000);
                    self::$socket->setRecvTimeout(20000);
                    self::$transport = new TFramedTransport(self::$socket, 1024, 1024);
                    self::$transport->open();
                    $protocol = new TBinaryProtocol(self::$transport);
                    self::$client = new TSCutWordClient($protocol);
                } catch (Exception $e) {
                    $msg  = "CutWord service socket connect failed";
                    $msg .= " service host: ".$host." port: ".$port;
                    $msg .= " Exception:".$e->getMessage();
                    $this->log($msg);
                };
            } else {
                return;
            }
        }
    }
    public function cutWord($string){
         $this->connectInterface();
         if (self::$client instanceof TSCutWordClient) {
             return self::$client->CutWord($string);
         }
    }
    public function multiCutWord($string){
         $this->connectInterface();
         if (self::$client instanceof TSCutWordClient) {
             return self::$client->MultiCutWord($string);
         }
    }

}

