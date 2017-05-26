<?php

/**
 * @brief [十分重要] 此文件只用于测试，不能在正式文件中使用
 * @brief finagle-php相关项目的单元测试文件可以include此文件
 */

if(!defined("CODE_BASE2")) {
    define("CODE_BASE2", dirname(__FILE__)."/../..");
}
if(!defined("GANJI_CONF")) {
    define("GANJI_CONF", dirname(__FILE__)."/../../../ganji_conf");
}
if(!defined("FINAGLE_BASE")) {
    define("FINAGLE_BASE", dirname(__FILE__));
}

$GLOBALS['THRIFT_ROOT'] = CODE_BASE2 . '/third_part/thrift-0.5.0';

require_once $GLOBALS['THRIFT_ROOT'] . '/Thrift.php';
require_once $GLOBALS['THRIFT_ROOT'] . '/transport/TSocket.php';
require_once $GLOBALS['THRIFT_ROOT'] . '/transport/THttpClient.php';
require_once $GLOBALS['THRIFT_ROOT'] . '/transport/TServerSocket.php';
require_once $GLOBALS['THRIFT_ROOT'] . '/transport/TTransportFactory.php';
require_once $GLOBALS['THRIFT_ROOT'].'/transport/TPhpStream.php';
require_once $GLOBALS['THRIFT_ROOT'] . '/transport/TFramedTransport.php';
require_once $GLOBALS['THRIFT_ROOT'] . '/protocol/TBinaryProtocol.php';


require_once FINAGLE_BASE . '/client/SF_THttpClient.php';
require_once FINAGLE_BASE.'/client/SF_ThriftClientFactory.php';
require_once FINAGLE_BASE.'/client/SF_HttpClientFactory.php';
require_once FINAGLE_BASE . '/client/SF_THttpClientFactory.php';
require_once FINAGLE_BASE.'/loadbalancer/SF_RandomLoadBalancerFactory.php';
require_once FINAGLE_BASE.'/loadbalancer/SF_RoundRobinLoadBalancerFactory.php';
require_once FINAGLE_BASE.'/builder/SF_ClientBuilder.php';
require_once FINAGLE_BASE.'/util/SF_Duration.php';

// require_once FINAGLE_BASE. "/client/SF_HttpClient.php";
// require_once FINAGLE_BASE. "/client/SF_HttpRequest.php";

//支持scribe
/*
require_once GANJI_CONF. '/GanjiLoggingConfig.class.php';
require_once FINAGLE_BASE. '/util/SF_GanjiLoggingConfig.php';
require_once CODE_BASE2. '/util/log/Logger.class.php';
Logger::setConfig(new SF_GanjiLoggingConfig());
*/

// 单元测试时使用下面日志类可把error信息打印到本地

require_once FINAGLE_BASE . '/test/lib/log/SF_DebugLogger.php';

require_once GANJI_CONF . "/GlobalConfig.class.php";
