<?PHP

require_once dirname(__FILE__) . '/../../../config/config.inc.php';
 
/**
 * 
 * @brief 日志类测试
 * @copyright (c) 2011 Ganji Inc.
 * @author    caifeng caifen@ganji.com duxiang duxiang@ganji.com
 * @date      时间: 2012-4-23:下午09:43:27
 * @version    1.0 
 *
 */
class LoggingConfig 
{
    public $enableScribe = false;
    public $logDir = '/tmp/';
//    public $logDir = 'E:\ganji_dev\ganji_v5\logs\\';
    
    public $recordMode = 2; // 1/默认的第一种, 2/第2种记录方式
    
    const BASE_CATEGORY = "test.web";        // 主站标示

    const DEBUG = 1; // Most Verbose
    const INFO = 2; // ...
    const WARN = 3; // ...
    const ERROR = 4; // ...
    const FATAL = 5; // Least Verbose
    
    private static $PRIORITY = array(
        '' =>  LoggingConfig::WARN,    
        );
        private static $BACKTRACE = array(
                'bt' => '',
                );         
    public function getConfig($category) {
        if( $category === FALSE ) 
            $scategory = self::BASE_CATEGORY;
        else
            $scategory = self::BASE_CATEGORY . '.' . $category;
        
        if( isset(self::$PRIORITY[$category] ))
            $priority = self::$PRIORITY[$category];
        else
            $priority = self::$PRIORITY[''];
        
                $backtrace = isset(self::$BACKTRACE[$category]);
        return array( $scategory , $priority, $backtrace);
    }    
}

include_once dirname(__FILE__) . '/../Logger.class.php';

class LoggerTest extends PHPUnit_Framework_TestCase {
    
    public $_configObj = null;
    
    public function testLog() {
        $config = new LoggingConfig();
        $config->recordMode = 1;
        $this->_initLogger($config);
        
        //测试 error
        $message = 'log_test_' . rand(10000, 20000);
        Logger::logError($message, 'bt');
        $line = file_get_contents( $this->_configObj->logDir . 'test.web.bt');
        $this->assertTrue(strpos($line, sprintf('[%d] %s', LoggingConfig::ERROR, $message)) !== false);
        @unlink( $config->logDir . 'test.web.bt');
        
        //测试 fatal
        $message = 'log_test_' . rand(10000, 20000);
        Logger::logFatal($message, 'fatal');
        $line = file_get_contents( $this->_configObj->logDir . 'test.web.fatal');
        $this->assertTrue(strpos($line, sprintf('[%d] %s', LoggingConfig::FATAL, $message)) !== false);
        @unlink( $config->logDir . 'test.web.fatal');
    }
    
    public function testLog2() {
        $config = new LoggingConfig();
        $config->recordMode = 2;
        $this->_initLogger($config);
        
        //测试 fatal, error
        $message = 'log_test_' . rand(10000, 20000);
        
        //测试 error
        Logger::logError( 'logError1' . $message, 'logError2');
        $line = file_get_contents( $this->_configObj->logDir . 'applog.error');
        $this->assertTrue(strpos($line, sprintf('test.web.logError2 [%d] logError1%s', LoggingConfig::ERROR, $message)) !== false);
        @unlink( $config->logDir . 'test.web.fatal');
        
        //测试 fatal
        Logger::logFatal( 'logFatal1' . $message, 'logFatal1');
        $line = file_get_contents( $this->_configObj->logDir . 'applog.fatal');
        $this->assertTrue(strpos($line, sprintf('test.web.logFatal1 [%d] logFatal1%s', LoggingConfig::FATAL, $message)) !== false);
        @unlink( $config->logDir . 'test.web.fatal');
    }
    
    protected function setUp() {
        echo "\n============LoggerTest start ==============\n";
    }
    
    private function _initLogger($LoggingConfig) {
        Logger::setConfig( $LoggingConfig );
        @mkdir( $config->logDir );    
        $this->_configObj = $LoggingConfig;
    }
    
    protected function tearDown()
    {
        echo "\n============LoggerTest end==============\n";
    }
}
