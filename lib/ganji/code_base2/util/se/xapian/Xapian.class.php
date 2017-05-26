<?PHP
/**
 * 用于查询xapian的php接口
 *
 * @author  yujinglei@ganji.com
 * @version v5
 * @since   2011-3-16
 *
 */

$GLOBALS['THRIFT_ROOT'] = CODE_BASE2 . '/third_part/thrift-0.5.0';
$GENDIR = dirname(__FILE__) . '/inc';

/** Include the Thrift base */
require_once $GLOBALS['THRIFT_ROOT'].'/Thrift.php';
/** Include the binary protocol */
require_once $GLOBALS['THRIFT_ROOT'].'/protocol/TBinaryProtocol.php';
/** Include the socket layer */
require_once $GLOBALS['THRIFT_ROOT'].'/transport/TSocketPool.php';
/** Include the socket layer */
require_once $GLOBALS['THRIFT_ROOT'].'/transport/TFramedTransport.php';
/** Include the generated code */
require_once $GENDIR.'/TSQueryAnalysisAgent.php';
require_once $GENDIR.'/query_analysis_agent_types.php';
require_once dirname(__FILE__) . '/../../datetime/DateTimeNamespace.class.php';

class Xapian {
    //sleep time
    const SLEEPTIME = 50000;

    private static $_SLEEP_TIME = array(
        1   => 60000,
        2   => 70000,
        3   => 70000,
        4   => 100000,
        5   => 200000,
        6   => 300000,
        7   => 400000,
        8   => 500000,
        9   => 800000,
        10  => 1000000,
    );

    //socket time out(ms)
    const SENDTIMEOUT = 500;
    //receive timeout(ms)
    const RECEIVETIMEOUT = 2000;
    //retry time
    const RETRY_TIMES = 10;
    //retry times
    const SOCKET_RETRY_TIMES = 3;
    //retry time
    private $socket_retry_time = 1;

    private $transport = null;
    /**
     * @var TSQueryAnalysisAgentClient
     */
    private $xapianClient;

    private $queryStartTime = null;

    /**
     * @brief 是否已经连接, true|已连接, false|未连
     * @var boolean
     */
    private $_isTransportCreated = false;

    /**
     * 初始化Xapian对象
     * @param $host string searchd的ip地址
     * @param $port int    searchd的port
     * @return null
     */
    public function __construct($config) {
        if (isset($config['host'])) {
            $this->hosts = array($config);
        } else {
            shuffle($config);
            $this->hosts = $config;
        }
    }

    protected function createTransport($hostItem) {
        if ($this->_isTransportCreated) {
            return true;
        }
        try{
            $this->socket = new TSocket($hostItem['host'], $hostItem['port']);
            $this->socket->setSendTimeout(self::SENDTIMEOUT);
            //            $this->socket->setDebug(TRUE);

            $framedsocket = new TFramedTransport($this->socket, 1024, 1024);
            $this->transport = $framedsocket;

            //$protocol = new TBinaryProtocol($this->transport);
            $protocol = new TBinaryProtocolAccelerated($this->transport);
            $protocol_out = new TBinaryProtocol($this->transport);
            //$this->xapianClient = new TSQueryAnalysisAgentClient($protocol);
            $this->xapianClient = new TSQueryAnalysisAgentClient($protocol, $protocol_out);
            return true;
        }
        catch(Exception $e){
            $msg  = "xapian socket fail ";
            $msg .= " xapian host:".$hostItem['host']." port:".$hostItem['port'];
            $msg .= " web host:".$_SERVER["SERVER_ADDR"]." name:".$_SERVER["SERVER_NAME"];
            $msg .= " Exception:".$e->getMessage();
            $this->log($msg);
            return false;
        }
    }

    /**
     * 打开xapian连接
     */
    public function openOnce($recordLogFlag = true){
        try{
            if (!$this->transport->isOpen()) {
                $this->transport->open();
            }
            return true;
        }
        catch(Exception $e){
            $msg  = "xapian socket fail time".$this->socket_retry_time;
            $msg .= " xapian host:".$this->socket->getHost()." port:".$this->socket->getPort();
            $msg .= " web host:".$_SERVER["SERVER_ADDR"]." name:".$_SERVER["SERVER_NAME"];
            $msg .= " Exception:".$e->getMessage();
            $this->socket_retry_time ++;
            if ($recordLogFlag) {
                $this->log($msg);
            }

            return false;
        }
    }

    /**
     * 打开xapian连接
     */
    public function open($recordLogFlag = true){
        for ($i=0; $i<Xapian::SOCKET_RETRY_TIMES; ++$i) {
            if ($this->openOnce($recordLogFlag)) {
                return true;
            }
            usleep(1000);
        }
        return false;
    }

    /**
     * 关闭xapian连接
     */
    public function close(){
        if ($this->transport && $this->transport->isOpen()) {
            $this->transport->close();
        }
    }
    
    /**
     * @brief 确保打开连接 打开联接
     * @return boolean, true|成功, false|失败
     */
    private function _assertOpen() {
        $hasAvailableXapian = false;
        foreach ($this->hosts as $host) {
            if (!$this->createTransport($host)) {
                continue;
            }
            if ($this->open()) {
                $hasAvailableXapian = true;
                $this->_isTransportCreated = true;
                break;
            }
        }
        if (!$hasAvailableXapian) {
            return false;
        }
        return true;
    }

    /**
     * 检索数据
     * @param $quersString    string 用于检索的条件
     * @return array 返回检索的结果
     */
    public function query($quersString) {
        $this->_debug('quersString=' . $quersString);
        try{
            if(!$this->_assertOpen()){
                return 0;
            }

            $this->queryStartTime  = DateTimeNamespace::getMicrosecond();
            $ret    = $this->xapianClient->Search($quersString);
            $this->_debug('query_id=' . $ret);
            $this->close();
            return $ret;
        }catch(Exception $e){
            $msg  = "xapian query get data fail";
            $msg .= " web host:".$_SERVER["SERVER_ADDR"]." name:".$_SERVER["SERVER_NAME"];
            $msg .= " query:" . $quersString;
            $msg .= " Exception:".$e->getMessage();
            $this->_debug('exception=' . $msg);
            $this->log($msg);
        }
        $this->close();
    }

    /**
     * 通过query_id异步获取查询结果
     * @param int $query_id
     */
    public function getResultByQueryId($query_id) {
        if (empty($query_id)) {
            return false;
        }
        $loop = 0;
        do {
            $loop++;
            $sleep_time = $this->getSleepTime($loop);
            // 第一次查询sleep时间，减去当前时间与发起请求的时间微秒差，
            if ($loop == 1) {
                $sleep_time = $sleep_time - (DateTimeNamespace::getMicrosecond() - $this->queryStartTime);
            }

            if ($sleep_time > 0) {
                usleep($sleep_time);
            }

            try {
                if(!$this->_assertOpen()){
                    continue;
                }

                if ($this->_isDebug()) {
                    $result = $this->xapianClient->DebugGetSearchResult($query_id);
                    $this->_debug($result);
                    if (isset($result->debug_info['6_total_num'])) {
                        $result->total_num = $result->debug_info['6_total_num'];
                    }
                } else {
                    $result = $this->xapianClient->GetSearchResult($query_id);
                }

                $this->close();
            }
            catch (Exception $e) {
                $this->close();
                $msg  = "xapian get data fail";
                $msg .= " web host:".$_SERVER["SERVER_ADDR"]." name:".$_SERVER["SERVER_NAME"];
                $msg .= " query_id:".$query_id;
                $msg .= " Exception:".$e->getMessage();
                $this->_debug('getResultByQueryId Exception=' . $msg);
                $this->log($msg);
                continue;
            }
            // 第三次查询之后，每次查询都先open，在close          
        } while ((!isset($result->flag) || $result->flag == 0) && $loop < self::RETRY_TIMES);

        if($result->flag == 0) {
            $msg  = "xapian get data result_flag return 0";
            $msg .= " id:" .$query_id;
            $this->log($msg);
            return false;
        }
        return array('data' => $result->return_value,'count' => $result->total_num);
    }

    /**
     * 全站检索，返回各子类目的帖子总数
     * @param $quersString    string 用于检索的条件
     * @return array 返回检索的结果
     */
    public function searchCount($quersString) {
        if (empty($quersString)) {
            return;
        }
        try{
            $catenation = false;
            $loop = 0;
            do{
                $loop++;
                if(!$this->_assertOpen()){
                    continue;
                }
                $catenation = true;
            } while (!$catenation && $loop < self::SOCKET_RETRY_TIMES);
            $this->_debug('searchCountStart:' . $quersString);
            $id = $this->xapianClient->SearchCount($quersString);
            usleep(self::SLEEPTIME);
            
            if ($this->_isDebug()) {
                $result = $this->xapianClient->DebugGetSearchResult($id);
                $this->_debug($result);
            } else {
                $result = $this->xapianClient->GetSearchResult($id);
            }
            $i = 1;
            while ($result->flag == 0 && $i <= self::RETRY_TIMES) {
                if ($this->_isDebug()) {
                    $result = $this->xapianClient->DebugGetSearchResult($id);
                    $this->_debug($result);
                } else {
                    $result = $this->xapianClient->GetSearchResult($id);
                }
                $sleep_time = $this->getSleepTime($i);
                usleep($sleep_time);
                $i++;
            }
            $this->_debug('searchCountEnd');
            $this->close();
            if(!isset($result->flag) || $result->flag == 0){
                $msg  = "xapian get count result_flag return 0";
                $msg .= "query:".$quersString;
                $this->log($msg);
                return;
            }
            return array_pop($result->return_value);
        } catch (Exception $e){
            $this->close();
            $msg  = "xapian get count fail";
            $msg .= " web host:".$_SERVER["SERVER_ADDR"]." name:".$_SERVER["SERVER_NAME"];
            $msg .= " query:".$quersString;
            $msg .= " Exception:".$e->getMessage();
            $this->log($msg);
        }

    }

    /**
     * 写日志
     * @see Logger.class.php
     */
    public function log($msg)
    {
        $this->_debug('log=' . $msg);
        if(class_exists('Logger')){
            Logger::logError($msg, 'xapian');
        }
    }

    /**
     * 获取每次请求的sleep时间。
     * 前两次100ms
     * @param $i int 请求次数
     * @return $sleep_time int sleep微秒数
     */
    public function getSleepTime($i){
        return self::$_SLEEP_TIME[$i];
    }

    /**
     * 设置每次请求的sleep时间。
     * @param $i int 请求次数
     * @return $sleep_time int sleep微秒数
     */
    public function setSleepTime($sleepTime){
        self::$_SLEEP_TIME = $sleepTime;
    }

    /**
     * 关闭连接
     */
    public function __destruct(){
        $this->close();
    }

    /**
     * @brief 内网ip, search_bebug = $GLOBALS['SEARCH_DEBUG_BACKEND']
     * @return boolean
     */
    private function _isDebug() {
        static $ret = null;
        if (is_bool($ret)) return $ret;

        include_once CODE_BASE2 . '/util/http/HttpNamespace.class.php';
        $searchDebug  = (int)HttpNamespace::getREQUEST('search_debug', 0);
        if ($searchDebug > 0) {
            include_once CODE_BASE2 . '/app/access_control_limit/AccessControlLimitNamespace.class.php';
            include_once GANJI_CONF . '/GlobalConfig.class.php';

            if (AccessControlLimitNamespace::isInternalIp(HttpNamespace::getIp(false))) {
                if (isset($GLOBALS['SEARCH_DEBUG_BACKEND']) && $searchDebug == $GLOBALS['SEARCH_DEBUG_BACKEND']) {
                    $ret = true;
                } else {
                    $ret = false;
                }
            }
        }
        return $ret;
    }

    private function _debug($info) {
        if ($this->_isDebug()) {
           if (is_object($info)) {
               $msg = new stdClass();
               foreach ($info as $key => $value) {
                  if ($key == 'return_value') {
                     continue;
                  }
                  $msg->$key = $value;
               }
           } else {
              $msg = $info;
           }
            printf("<pre>%s%s</pre>", date('H:i:s', time()), htmlspecialchars(var_export($msg, true)));
        }
    }
}