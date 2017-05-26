<?php

/**
 * @Copyright (c) 2014 Ganji Inc.
 * @file          /code_base2/util/finagle/util/SF_DebugLogger.php
 * @author        zhaoweiguo@ganji.com
 * @date          2014-07-04
 *
 * 主要用于测试平台进行单元测试时调试时使用，正式平台一定不要include
 */

require_once CODE_BASE2. "/util/log/ScribeLogger.class.php";

// V3在引用CODE_BASE2的一些util代码时会出现Logger重复定义的问题,因此先判断下
if (!class_exists('Logger')) {

    /**
     * @class: Logger
     * @PURPOSE:  基于/code_base2/util/log/Logger 修改，主要用于单元测试
     */
    class Logger {

        /**
         * 记录一条Warning日志
         * @param[in] string message 日志的正文
         * @param[in] string category 日志对应的子类别，在发送到scribed时，会在前面加上应用的类别前缀
         */
        static function logWarn($message, $category = FALSE) {
            self :: _log($message, ScribeLogger :: WARN, $category);
        }

        /**
         * 记录一条Error日志
         * @param[in] string message 日志的正文
         * @param[in] string category 日志对应的子类别，在发送到scribed时，会在前面加上应用的类别前缀
         */
        static function logError($message, $category = FALSE) {
            self :: _log($message, ScribeLogger :: ERROR, $category);
        }

        /**
         * 记录一条Fatal日志
         * @param[in] string message 日志的正文
         * @param[in] string category 日志对应的子类别，在发送到scribed时，会在前面加上应用的类别前缀
         */
        static function logFatal($message, $category = FALSE) {
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
            var_dump($message);
        }

    }
}