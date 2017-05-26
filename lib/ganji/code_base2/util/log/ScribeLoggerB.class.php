<?php
/**
 * 实现Scribe日志输出的类
 * 
 * @author  caifeng@ganji.com duxiang@ganji.com
 * @version v5
 * @since   2011-3-10
 * 
 */
 
include_once $GLOBALS['THRIFT_ROOT'] . '/packages/scribe/scribe.php';
include_once $GLOBALS['THRIFT_ROOT'] . '/transport/TSocket.php';
include_once $GLOBALS['THRIFT_ROOT'] . '/transport/TFramedTransport.php';
include_once $GLOBALS['THRIFT_ROOT'] . '/protocol/TBinaryProtocol.php';

/**
 * Class documentation
 */
class ScribeLoggerB
{

    const DEBUG = 1; // Most Verbose
    const INFO = 2; // ...
    const WARN = 3; // ...
    const ERROR = 4; // ...
    const FATAL = 5; // Least Verbose
    const OFF = 6; // Nothing at all.
    
    const LOG_OPEN    = 1;
    const OPEN_FAILED = 2;
    const LOG_CLOSED  = 3;
    
    private static $_LOG_CATEGORY = array(
        self::DEBUG => 'debug',
        self::INFO => 'info',
        self::WARN => 'warn',
        self::ERROR => 'error',
        self::FATAL => 'fatal',
    );

    /* Public members: Not so much of an example of encapsulation, but that's okay. */
    private static $_defaultPriority   = self::DEBUG;
    private static $_dateFormat        = "Y/m/d G:i:s";
    private static $_transport         = NULL;
    private static $_scribe_client     = NULL;
    
    private $_logCategory = '';
    private $_priority          = self::INFO;
    private $_extra = '';

    private static $instance = null;

    /**
     * 获取某个类别的ScribeLogger实例，用以实现不同类别不同的输出级别控制
     * @param string logCategory 目标类别，参照config中的配置
     * @param integer priority   允许输出的最小LEVEL
     * @param array extra 是否需要在日志正文中输出额外的内容 
     */
    public static function instance($logCategory, $priority = FALSE, $extra = FALSE) {
        if (!self::$instance) {
            if($priority === FALSE) $priority = self::$_defaultPriority;
            self::$instance = new self($logCategory, $priority, $extra);
        }
        self::$instance->setLogCategory($logCategory);
        return self::$instance;
    }
    
    /**
     * 创建连接
     */
    private static function _init_transport()
    {
        $socket = new TSocket('localhost', 11463);
        self::$_transport = new TFramedTransport($socket);
        $protocol = new TBinaryProtocolAccelerated(self::$_transport);                            
        $protocol_out = new TBinaryProtocol(self::$_transport);
        self::$_scribe_client = new scribeClient($protocol, $protocol_out);
#$socket->setDebug(true);
        try {
            self::$_transport->open();
        } catch(Exception $e) {
            error_log( "ScribeLogger::_init_transport connect to scribed failed");
        }
    }
    
    /**
     * 创建一个ScribeLogger实例
     * @param string logCategory 目标类别，参照config中的配置
     * @param integer priority   允许输出的最小LEVEL
     * @param array extra 是否需要在日志正文中输出额外的内容 
     */
    public function __construct($logCategory, $priority, $extra)
    {
        $this->_logCategory = $logCategory;
        $this->_priority = $priority;
        if( $extra )
        {
            foreach( $extra as $k => $v) {
                if( $this->_extra ) $this->_extra.= ' ';
                $this->_extra .= "@$k=$v";
            }
        }
        self::_init_transport();
    }

    public function __destruct()
    {
    }

    /**
     * INFO日志
     */
    public function logInfo($line)
    {
        $this->log($line, self::INFO);
    }

    /**
     * DEBUG日志
     */
    public function logDebug($line)
    {
        $this->log($line, self::DEBUG);
    }

    /**
     * WARN日志
     */
    public function logWarn($line)
    {
        $this->log($line, self::WARN);
    }

    /**
     * ERROR日志
     */
    public function logError($line)
    {
        $this->log($line, self::ERROR);
    }

    /**
     * FATAL日志
     */
    public function logFatal($line)
    {
        $this->log($line, self::FATAL);
    }

    /**
     * @brief 日志输出的实现函数, 记录所有的日志, 暂时不做级别限制
     */
    public function log($line, $priority )
    {
        $time = $this->_getTimeLine($priority);
        if( $this->_extra ) {
            $extra = $this->_extra;
            $msg = "$extra $time $line";
        } else {
            $msg = "$time $line";
        }
        $entry = new LogEntry( array( 
            'category' => self::getCategory($priority),
            'message'  => sprintf('%s %s', $this->_logCategory, $msg)));
        try {
            self::$_scribe_client->Log(array($entry));
        } catch(Exception $e) {
            error_log( 'ScribeLogger::log write to scribed error.');
        }
    }

    public function logRaw($category, $msg) {
        $entry = new LogEntry( array(
            'category' => $category,
            'message'  => $msg ));
        try {
            self::$_scribe_client->Log(array($entry));
        } catch(Exception $e) {
            error_log( 'ScribeLoggerB::logRaw write to scribed error.');
        }
    }

    public function logMonitor($line) {
        $entry = new LogEntry( array(
            'category' => "applog.mon",
            'message'  => $this->_getTimeLine(self::WARN) . ' ' . $line) );
        try {
            self::$_scribe_client->Log(array($entry));
        } catch(Exception $e) {
            error_log( 'ScribeLogger::log write to scribed error.');
        }         
    } 
    /**
     * @brief 获取category
     * @param $priority
     * @return string
     */
    public static function getCategory($priority) {
        return 'applog.' . (isset(self::$_LOG_CATEGORY[$priority]) ? self::$_LOG_CATEGORY[$priority] : self::$_LOG_CATEGORY[self::$_defaultPriority]);
    }

    public function setLogCategory($logCategory) {
        $this->_logCategory = $logCategory;
    }

    private function _getTimeLine($level)
    {
        $time = date(self::$_dateFormat);

        switch($level)
        {
            case self::INFO:
                return "$time [INFO]";
            case self::WARN:
                return "$time [WARN]";
            case self::DEBUG:
                return "$time [DEBUG]";
            case self::ERROR:
                return "$time [ERROR]";
            case self::FATAL:
                return "$time [FATAL]";
            default:
                return "$time [LOG]";
        }
    }
}
