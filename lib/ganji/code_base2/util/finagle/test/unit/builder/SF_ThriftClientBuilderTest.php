<?php
require_once dirname(__FILE__) . '/../../../SF_Bootstrap.php';

require_once FINAGLE_BASE . "/test/lib/hellodemo/hello_types.php";
require_once FINAGLE_BASE . "/test/lib/hellodemo/Hello.php";
require_once FINAGLE_BASE . "/test/lib/hellodemo/ThriftHelloServer.php";
require_once FINAGLE_BASE . '/client/SF_HttpRequest.php';

class SF_ThriftClientBuilderTest extends  PHPUnit_Framework_TestCase {
	private static $host="127.0.0.1";
	private static $port=8888;
	private static $serviceName="/soa/services/hello";
	private static $helloServer;
	
	public static function setUpBeforeClass() {
        /* 由于jar包到测试平台总出问题，暂把单元测试放到一专门机器上
        self::$helloServer = new ThriftHelloServer();
		self::$helloServer->start(self::$serviceName, self::$host, self::$port);
		//sleep的原因是等待服务注册完成，然后才可以访问
		sleep(2);
        */
        echo "[unittest start]" . __CLASS__ ."\n";
        self::$host = "192.168.35.141";
	}
	
	public function testThriftHostsBuilder() {
        echo "[unittest] from testThriftHostsBuilder";
		$builder = new SF_ClientBuilder();
		$builder->tcpConnectTimeout(new SF_Duration(10, SF_Duration::UNIT_SECONDS))
            ->clientFactory(new SF_ThriftClientFactory("HelloClient"))
            ->loadBalance(new SF_RandomLoadBalancerFactory())
            ->retries(3)
            ->hosts(array(array("host"=>self::$host,"port"=>self::$port,"weight"=>1)))
            ->destName(self::$serviceName);

		$service=$builder->build();
		$result = $service->hi("kaka");
		//var_dump($result);
		$this->assertEquals("hi,kaka",$result);
	}

	public function testThriftBuilder() {
        echo "[unittest] from testThriftBuilder";
        SF_ServiceState::removeState(self::$serviceName);
		$builder = new SF_ClientBuilder();
		$builder->tcpConnectTimeout(new SF_Duration(10, SF_Duration::UNIT_SECONDS))
            ->clientFactory(new SF_ThriftClientFactory("HelloClient"))
            ->loadBalance(new SF_RandomLoadBalancerFactory())
            ->retries(3)
            ->hosts(array(array("host"=>self::$host,"port"=>self::$port,"weight"=>1)))
            ->destName(self::$serviceName);

		$service=$builder->build();
		$result = $service->hi("kaka");
		//var_dump("test from regcenter");
		//var_dump($result);
		$this->assertEquals("hi,kaka",$result);
	}


	public static function tearDownAfterClass() {
		//self::$helloServer->stop();
	}
}