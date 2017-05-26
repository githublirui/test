<?php
/**
 * HIVE查询接口
 *
 * @category  CODE_BASE2
 * @package   Util
 * @author    Wanglei <wanglei@ganji.com>
 * @since     2013-6-7
 * @copyright Copyright (c) 2005-2013 GanJi Inc. (http://www.ganji.com)
 * @version   $Id: HiveNamespace.class.php 184389 2013-06-07 02:36:18Z wanglei $
 */

require dirname(__FILE__) . '/include/hive.php';
require_once CODE_BASE2 . '/util/log/Logger.class.php';
require_once GANJI_CONF . '/InterfaceConfig.class.php';

class HiveNamespace {

    // WEB平台
    const PLATFORM_WEB = 'web';

    // 手机平台
    const PLATFORM_MOBILE = 'mobile';

    // 发送超时(毫秒)
    const SEND_TIMEOUT = 200;

    // 接受超时(毫秒)
    const RECV_TIMEOUT = 5000;

    private static $_SOCKET = null;

    private static $_TRANSPORT = null;

    private static $_PROTOCOL = null;

    private static $_HANDLER = null;

    /**
     * 创建HIVE任务
     * @param string $sql 查询语句
     * @param string $env 查询平台 web or mobile
     * @throws Exception
     * @return mixed 返回任务ID或者false
     */
    public static function submitTask($sql, $env = self::PLATFORM_WEB) {
        try {
            if ($env != self::PLATFORM_WEB && $env != self::PLATFORM_MOBILE) {
                throw new Exception('未知平台的查询:' . $env);
            }
            self::_checkHandler();
            $uniqId = $_SERVER['SCRIPT_NAME'];
            $taskId = self::$_HANDLER->submitTask($uniqId, $env, $sql);
            if (intval($taskId) > 0) {
                return $taskId;
            }
        } catch (Exception $e) {
            self::close();
            $msg = 'HIVE任务创建失败:' . $e->getMessage() . '|' . $_SERVER['SERVER_NAME'] . '[' . $_SERVER['SERVER_ADDR'] . ']';
            self::_log($msg);
        }
        return false;
    }

    /**
     * 轮询获取HIVE任务结果
     * @param integer $taskId 任务ID
     * @param integer $retryTime 重试次数(必须为正数)
     * @param integer $sleepTime sleep时间(毫秒)
     * @throws Exception
     * @return mixed 返回结果或false
     */
    public static function getResultByPolling($taskId, $retryTime, $sleepTime) {
        try {
            if ($taskId < 1 || $retryTime < 0 || $sleepTime < 0) {
                throw new Exception('参数不合法:$taskId='. $taskId . '$retryTime=' . $retryTime . ',$sleepTime=' . $sleepTime);
            }
            while($retryTime > 0) {
                $result = self::getResultByTaskId($taskId);
                if ($result !== false) {
                    self::close();
                    return $result;
                }
                usleep($sleepTime * 1000);
                $retryTime--;
            }
            self::close();
        } catch (Exception $e) {
            self::close();
            $msg = 'HIVE轮询失败:' . $e->getMessage() . '|' . $_SERVER['SERVER_NAME'] . '[' . $_SERVER['SERVER_ADDR'] . ']';
            self::_log($msg);
        }
        return false;
    }

    /**
     * 获取HIVE任务结果
     * @param integer $taskId 任务ID
     * @throws Exception
     * @return mixed 返回结果或false
     */
    public static function getResultByTaskId($taskId) {
        try {
            $taskId = intval($taskId);
            if ($taskId <= 0) {
                throw new Exception('任务号不合法:' . $taskId);
            }
            self::_checkHandler();
            if (self::$_HANDLER->isTaskFinished($taskId)) {
                $uri = self::$_HANDLER->getResultURI($taskId);
                $resultContent = self::_getResultContent($uri);
                self::close();
                return $resultContent;
            }
        } catch (Exception $e) {
            self::close();
            $msg = 'HIVE查询失败:' . $e->getMessage() . '|' . $_SERVER['SERVER_NAME'] . '[' . $_SERVER['SERVER_ADDR'] . ']';
            self::_log($msg);
        }
        self::close();
        return false;
    }

    /**
     * 关闭连接句柄
     */
    public static function close() {
        if (is_object(self::$_TRANSPORT) && self::$_TRANSPORT->isOpen()) {
            self::$_TRANSPORT->close();
        }
    }

    /**
     * 检查句柄
     */
    private static function _checkHandler() {
        if (!self::$_HANDLER instanceof HiveClient) {
            try {
                self::$_SOCKET = new TSocket(InterfaceConfig::HIVE_IP, InterfaceConfig::HIVE_PORT);
                self::$_SOCKET->setSendTimeout(self::SEND_TIMEOUT);
                self::$_SOCKET->setRecvTimeout(self::RECV_TIMEOUT);
                self::$_TRANSPORT = new TFramedTransport(self::$_SOCKET);
                self::$_PROTOCOL = new TBinaryProtocol(self::$_TRANSPORT);
                self::$_HANDLER = new HiveClient(self::$_PROTOCOL);
            } catch (Exception $e) {
                self::close();
                $msg = 'HIVE连接失败:' . $e->getMessage() . '|' . $_SERVER['SERVER_NAME'] . '[' . $_SERVER['SERVER_ADDR'] . ']';
                self::_log($msg);
            }
        }
        if (!self::$_TRANSPORT->isOpen()) {
            self::$_TRANSPORT->open();
        }
    }

    /**
     * 根据URL获取内容
     * @param string $url
     * @return string
     */
    private static function _getResultContent($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * 记录错误日志
     * @param string $msg
     */
    private static function _log($msg) {
        if (class_exists('Logger')) {
            Logger::logError($msg, 'hive');
        }
    }
}