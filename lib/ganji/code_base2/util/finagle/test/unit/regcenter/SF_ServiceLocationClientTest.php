<?php
require_once dirname(__FILE__) . '/../../../SF_Bootstrap.php';

require_once FINAGLE_BASE . "/test/lib/hellodemo/hello_types.php";
require_once FINAGLE_BASE . "/test/lib/hellodemo/Hello.php";
require_once FINAGLE_BASE . "/test/lib/hellodemo/ThriftHelloServer.php";
require_once FINAGLE_BASE . '/client/SF_HttpRequest.php';


class SF_ServiceLocationClientTest extends PHPUnit_Framework_TestCase {
	private static $host="gordon-ganji.local";
	private static $port=8888;
	private static $serviceName="/soa/services/hello";
	private static $key = "gordon-ganji.local:8888";
	
	public static function setUpBeforeClass() {

	}
    public function testGetServiceStateDataModels() {
        $balancer = new SF_RandomLoadBalancer();
        $builder = new SF_ClientBuilder();
        $builder->tcpConnectTimeout(new SF_Duration(10, SF_Duration::UNIT_SECONDS))
            ->loadBalance(new SF_RandomLoadBalancerFactory())
            ->retries(3)
            ->destName("hello");
        $models = SF_ServiceLocationClient::INSTANCE()->getServiceStateDataModels($builder);

        $nodeStates = $models->getNodeStates();
        $key = current($nodeStates)->key();

        $this->assertEqual($key, self::$key);


    }
    
    public static function tearDownAfterClass() {
    	//self::$helloServer->stop();
    }
}