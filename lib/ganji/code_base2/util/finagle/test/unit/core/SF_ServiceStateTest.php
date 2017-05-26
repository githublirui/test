<?php
require_once dirname(__FILE__) . '/../../../SF_Bootstrap.php';

require_once FINAGLE_BASE . '/unittest/Hello.php';
require_once FINAGLE_BASE . '/unittest/hello_types.php';

class SF_ServiceStateTest extends PHPUnit_Framework_TestCase {
//    private static $host="127.0.0.1";
//    private static $port=8888;
    private static $serviceName="/soa/services/hello";
//    private static $helloServer;

	public function setUp() {

	}
	
	public function testGetState() {
        $builder = new SF_ClientBuilder();

        $builder->tcpConnectTimeout(new SF_Duration(10, SF_Duration::UNIT_SECONDS))
            ->clientFactory(new SF_ThriftClientFactory("HelloClient"))
            ->loadBalance(new SF_RandomLoadBalancerFactory())
            ->retries(3)
            ->destName(self::$serviceName);

        $builder->build();

        print_r(SF_ServiceState::INSTANCE()->getState($builder));

	}
	
	public function tearDown() {
		
	}
}