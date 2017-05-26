<?php 
/**
 * 命令行模式调试类， 记录日志，打开调试
 * @author duxiang
 *
 */
class CronDebugNamespace {

    private static $_debug = false;

    public static function setDebug($bool = false) {
        self::$_debug = $bool;
    }

    /**
     * @brief 记录日志 logError
     * @see self::_log()
     */
    public function logError($msg = '', $category = 'mscli') {
        self::_log('logError', $msg, $category);
    }
    
    /**
     * @brief 记录日志 logWarn
     * @see self::_log()
     */
    public function logWarn($msg = '', $category = 'mscli') {
        self::_log('logWarn', $msg, $category);
    }

    /**
     * @brief 记录日志logInfo
     * @see self::_log()
     */
    public function logInfo($msg = '', $category = 'mscli') {
        self::_log('logInfo', $msg, $category);
    }
    
    public static function printInfo($msg = '', $category = 'mscli') {
        printf("%s - %s\n", date('Y-m-d H:i:s', time()), $msg);
    }
    
    /**
     * @brief 记录日志
     * @param sstring $logMethod logError|logWarn|logInfo...
     * @param $msg
     * @param $category 类别
     * @return void
     */
    private function _log($logMethod = '', $msg = '', $category = 'mscli') {
        if (self::$_debug == true) {
            printf("%s %s - %s\n", $logMethod, date('Y-m-d H:i:s', time()), $msg);
        }

        if (class_exists('Logger') && method_exists('Logger', $logMethod)) {
            Logger::$logMethod($msg, $category);
        }
    }
    
    /**
     * @brief 获取当前的微秒数
     * @return integer
     */
    public static function microtimeFloat() {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
    
    /**
     * @breif 尝试锁一个文件,独占锁定
     * @param string $filenameLock 锁定的文件
     * @return boolean true|锁定成功， false|锁定失败
     */
    public static function tryLockFile($filenameLock = '') {
        static $handle = null;
        if (!file_exists($filenameLock)) false;

        $handle = fopen($filenameLock, 'r');
        return flock($handle, LOCK_EX|LOCK_NB);
    }
}