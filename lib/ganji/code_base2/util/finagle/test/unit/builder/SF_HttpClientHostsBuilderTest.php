<?php
require_once dirname(__FILE__) . '/../../../SF_Bootstrap.php';

require_once FINAGLE_BASE . "/test/lib/hellodemo/hello_types.php";
require_once FINAGLE_BASE . "/test/lib/hellodemo/Hello.php";
require_once FINAGLE_BASE . "/test/lib/hellodemo/ThriftHelloServer.php";
require_once FINAGLE_BASE . '/client/SF_HttpRequest.php';

class SF_HttpClientHOstsBuilderTest extends  PHPUnit_Framework_TestCase {
	//@todo tobe rewrite
	public function  setUp() {
		//加setUp是为了ServiceLocationClient有静态方法，无法mock。所以注册一个服务
		$this->host="127.0.0.1";
		$this->port=8080;
		$this->serviceName="/soa/services/hello_http";
	}
	
	public function testHttpBuilder() {
		$builder = new SF_ClientBuilder();
		$builder->tcpConnectTimeout(new SF_Duration(10, SF_Duration::UNIT_SECONDS))
						->clientFactory(new SF_HttpClientFactory())
						->loadBalance(new SF_RandomLoadBalancerFactory())
						->httpPath("/")
						->hosts(array(array("host"=>"127.0.0.1","port"=>8080,"weight"=>1)))
						->destName("/soa/services/hello_http")
						->retries(3);
		
		$client = $builder ->build();
		$request = new SF_HttpRequest("", "get", "/");
		$result= $client->execute($request);
		$this->assertEquals("ok",$result);
	}
	
	public function tearDown() {
	}
	
}