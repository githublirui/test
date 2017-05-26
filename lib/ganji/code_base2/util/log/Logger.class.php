<?php

/**
 * @Copyright (c) 2011 Ganji Inc.
 * @file          /code_base2/util/log/Logger.class.php
 * @author        caifeng@ganji.com
 * @date          2011-03-15
 *
 * 记录应用日志的全局类
 */

// V3在引用CODE_BASE2的一些util代码时会出现Logger重复定义的问题,因此先判断下
if (!class_exists('Logger')) {

include_once dirname(__FILE__) . '/ScribeLogger.class.php';
include_once dirname(__FILE__) . '/ScribeLoggerB.class.php';

/**
 * @class: Logger
 * @PURPOSE:  Logger是一个静态类，能够根据配置方便地将各种类别的信息写到不同的日志监听端中。
 *            日志监听端分为：本地文本文件、数据库和队列
 */
class Logger {

    private static $_config = null; ///< 全局的config对象

    /**
     * @brief 初始化Logger的配置
     * @param[in] array config: Logger的全局配置信息
     */
    static function setConfig( $config ) {
        Logger::$_config = $config;
    }

    /**
     * @brief 获取Logger的配置
     * @return array config
     */
    static function getConfig() {
        return Logger::$_config;
    }

    static private function formatExtInfo() {
        // 在 5.3.6 之前，仅仅能使用的值是 TRUE 或者 FALSE，分别等于是否设置 DEBUG_BACKTRACE_PROVIDE_OBJECT 选项。
        $stacks = debug_backtrace(false);
        foreach ($stacks as $key => &$stack) {
            unset($stack['args']);
        }
        $stack_clean = array_slice($stacks, 2); // remove myself and logXXX
        $request = $_SERVER;
        unset( $request['HTTP_COOKIE'] );
        $info = array( "callstack" => $stack_clean, //$stack_clean,
                       "request" => $request );
        return "@[php" . @json_encode( $info ) . "@]";

    }
    /**
     * 记录一条Warning日志
     * @param[in] string message 日志的正文
     * @param[in] string category 日志对应的子类别，在发送到scribed时，会在前面加上应用的类别前缀
     */
    static function logWarn($message, $category = FALSE) {
        self::_formatMessage($message);
        $message .= "\n" . self :: formatExtInfo();
        self :: _log($message, ScribeLogger :: WARN, $category);
    }

    /**
     * 记录一条Error日志
     * @param[in] string message 日志的正文
     * @param[in] string category 日志对应的子类别，在发送到scribed时，会在前面加上应用的类别前缀
     */
    static function logError($message, $category = FALSE) {
        self::_formatMessage($message);
        $message .= "\n" . self :: formatExtInfo();
        self :: _log($message, ScribeLogger :: ERROR, $category);
    }

    /**
     * 记录一条Fatal日志
     * @param[in] string message 日志的正文
     * @param[in] string category 日志对应的子类别，在发送到scribed时，会在前面加上应用的类别前缀
     */
    static function logFatal($message, $category = FALSE) {
        self::_formatMessage($message);
        $message .= "\n" . self :: formatExtInfo();
        self :: _log($message, ScribeLogger :: FATAL, $category);
    }

    /**
     * 记录一条Info日志
     * @param[in] string message 日志的正文
     * @param[in] string category 日志对应的子类别，在发送到scribed时，会在前面加上应用的类别前缀
     */
    static function logInfo($message, $category = FALSE) {
        self :: _log($message, ScribeLogger :: INFO, $category);
    }

    /**
     * 记录一条Debug日志
     * @param[in] string message 日志的正文
     * @param[in] string category 日志对应的子类别，在发送到scribed时，会在前面加上应用的类别前缀
     */
    static function logDebug($message, $category = FALSE) {
        self :: _log($message, ScribeLogger :: DEBUG, $category);
    }

    /**
     * 实现日志输出的内部函数
     * 根据配置的不同，日志可以送给本机的Scribed服务，也可以送到临时目录下
     * @param[in] string messsage 日志的正文
     * @param[in] enum priority 日志输出的级别
     * @param[in] string category 日志的类别，格式 {产品线}.{模块1}.{子模块1}...
     */
    private static function _log($message, $priority, $category) {
        if( !Logger::$_config ) return;
        $conf = Logger::$_config->getConfig($category);
        if( count($conf) == 2 ) {
            list ($logCategory, $defPriority) = $conf;
            $enableBacktrace = null;
        } else {
            list ($logCategory, $defPriority, $enableBacktrace) = $conf;
        }

        #if( $enableBacktrace )
    #    $message .= "\n" . print_r(debug_backtrace(),true);
        if (Logger::$_config->enableScribe) {
            if (isset(Logger::$_config->recordMode) && Logger::$_config->recordMode == 2) {
                ScribeLoggerB :: instance($logCategory, $defPriority)->log($message, $priority);
            } else {
                ScribeLogger :: instance($logCategory, $defPriority)->log($message, $priority);
            }
        } else {
            if (!@ file_exists(Logger::$_config->logDir)){
                @ mkdir(Logger::$_config->logDir);
            }

            if (isset(Logger::$_config->recordMode) && Logger::$_config->recordMode == 2) {
                file_put_contents(Logger::$_config->logDir . ScribeLoggerB::getCategory($priority), date("Y-m-d H:i:s", time()) . " $logCategory [$priority] $message\n", FILE_APPEND);
            } else {
                file_put_contents(Logger::$_config->logDir . $logCategory, date("Y-m-d H:i:s", time()) . " [$priority] $message\n", FILE_APPEND);
            }
        }
    }

    /**
     * @breif 格式化截取信息长度
     * @param $message
     * @param $maxLength 默认3k
     */
    private static function _formatMessage(&$message, $maxLength = 3000) {
        // 确保信息是string
        if (!is_string($message)) {
            $message = var_export($message, true);
        }
        // 超过3k做消息内容的截取
        if (strlen($message) < $maxLength) return;
        $message = substr($message, 0, $maxLength);
    }

    /**
     * 直接输出一条日志，用于特定场景标准的API无法使用
     * @param[in] string message 日志的正文
     * @param[in] string priority 日志对应的子类别，在发送到scribed时，会在前面加上应用的类别前缀
     * @param[in] string category 日志对应的子类别，该类别会直接送到scribed
     * @return void
     */
    static function logRaw($message, $priority, $category) {
        if( !Logger::$_config ) return;

        if (Logger::$_config->enableScribe) {
            ScribeLogger :: instance($category, ScribeLogger :: DEBUG)->log($message, $priority);
        } else {
            if (!@ file_exists(Logger::$_config->logDir)) {
                @ mkdir(Logger::$_config->logDir);
            }
            file_put_contents(Logger::$_config->logDir . $category, date("Y-m-d H:i:s", time()) . " [$priority] $message\n", FILE_APPEND);
        }
    }
    /**
     * @param[in] string $key
     * @param[in] string $category
     * @param[in] string $tag
     * @param[in] int $v
     */
    static function logRta($key, $category, $tag, $v=1) {
        if( !Logger::$_config ) return;

        if (Logger::$_config->enableScribe) {
            ScribeLogger :: instance("rta." . Logger::$_config->getBaseCategory(), ScribeLogger :: INFO)->log("k=$key&c=$category&tag=$tag&v=$v", ScribeLogger :: INFO);
        }
    }

    /**
     * direct log to
     * @param $category
     * @param $msg
     * @param integer $priority 日志级别
     */
    static function logDirect($category, $msg, $priority = ScribeLogger::INFO) {
        if( !Logger::$_config ) return;
        if(Logger::$_config->enableScribe ) {
        	$priority = in_array($priority, array(1,2,3,4,5,6)) ? $priority : ScribeLogger :: INFO;
            ScribeLoggerB :: instance("_", $priority)->logRaw($category, $msg);
        }
    }
}
}