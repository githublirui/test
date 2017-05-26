<?php
require_once dirname(__FILE__) . '/../../../SF_Bootstrap.php';

require_once FINAGLE_BASE . "/test/lib/hellodemo/hello_types.php";
require_once FINAGLE_BASE . "/test/lib/hellodemo/Hello.php";
require_once FINAGLE_BASE . "/test/lib/hellodemo/ThriftHelloServer.php";
require_once FINAGLE_BASE . '/client/SF_HttpRequest.php';


class SF_HttpClientBuilderTest extends  PHPUnit_Framework_TestCase {

    private static $host;
    private static $port;
    private static $serviceName;
    private static $helloServer;

	// @todo  tobe rewrite  --- gordon
	public static function  setUpBeforeClass() {
		//加setUp是为了ServiceLocationClient有静态方法，无法mock。所以注册一个服务
        echo "[unit start]". __CLASS__ ." \n";
        self::$host = "192.168.35.141";
        self::$port=8888;
        self::$serviceName="/soa/services/hello";
/*
		self::$host="127.0.0.1";
        self::$port=8888;
        self::$serviceName="/soa/services/hello";
        self::$helloServer = new ThriftHelloServer();
        self::$helloServer->start(self::$serviceName, self::$host, self::$port);
		//sleep的原因是等待服务注册完成，然后才可以访问
		sleep(20);
*/
	}
	
	public function testHttpBuilder() {
		$clientStub = $this->getMockBuilder('SF_HttpClient')
                ->setConstructorArgs(
                    array(
                        self::$host,self::$port,
                            new SF_Duration(10, SF_Duration::UNIT_SECONDS)
                            )
                    )
                ->getMock();
		
		$clientStub->expects($this->any())
						   ->method("execute")
						   ->will($this->returnValue('ok'));
		
		/* $clientStub->expects($this->any())
							->method("__call")
							->will($this->returnValue('ok')); */
		
		$factoryStub = $this->getMockBuilder('SF_HttpClientFactory')
										  ->getMock();
		
		$factoryStub ->expects($this->any())
							 ->method("getClient")
							 ->will($this->returnValue($clientStub));
		
		$builder = new SF_ClientBuilder();
		$builder->tcpConnectTimeout(new SF_Duration(10, SF_Duration::UNIT_SECONDS))
            ->clientFactory($factoryStub)
            ->loadBalance(new SF_RandomLoadBalancerFactory())
            ->httpPath("/a/b")
            ->destName("/soa/services/hello")
            ->hosts(array(array("host"=>self::$host,"port"=>self::$port,"weight"=>1)))
            ->retries(3);
		
		$client = $builder ->build();
		$request = new SF_HttpRequest("http", "GET", "/a/b");
		$result= $client->execute($request);
		$this->assertEquals("ok",$result);
	}
	
	public static function tearDownAfterClass() {
		//self::$helloServer->stop();
	}
	
}