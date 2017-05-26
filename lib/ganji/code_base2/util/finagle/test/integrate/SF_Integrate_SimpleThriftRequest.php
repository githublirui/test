<?php
require_once dirname(__FILE__) . '/../../../SF_Bootstrap.php';

require_once FINAGLE_BASE . "/unittest/hello_types.php";
require_once FINAGLE_BASE . "/unittest/Hello.php";
require_once FINAGLE_BASE . "/unittest/ThriftHelloServer.php";

class SF_ThriftClientTest {
    // @todo needed be implements

    protected function setUp() {
		//server 命令
		// java -jar -Dservice.host=127.0.0.1 -Dservice.port=18080 -Dservice.announce=zk!10.3.255.222:2181
		//!/soa/services/hello!1 finagle.service.deploy-1.0-jar-with-dependencies.jar
		$this->host="127.0.0.1";
		$this->port=8888;
		$serviceName="/soa/services/hello";
		$this->helloServer = new ThriftHelloServer();
		$this->helloServer->start($serviceName, $this->host, $this->port);
		sleep(10);
	}
	
	public  function testHi() {
		$builder = new SF_ClientBuilder();
		$builder->tcpConnectTimeout(new SF_Duration(10, SF_Duration::UNIT_MILLISECONDS));
        $builder->build();
		$clientFactory = new SF_ThriftClientFactory("HelloClient",true);
		$client = $clientFactory->getClient($builder,$this->host, $this->port);
		$result = $client->hi("kaka");
		$this->assertEquals("hi,kaka",$result);

        $clientFactory = new SF_ThriftClientFactory("HelloClient",false);
        $client = $clientFactory->getClient($builder,$this->host, $this->port);
        $result = $client->hi("kaka");
        $this->assertEquals("hi,kaka",$result);

    }
	
	public function tearDown() {
		$this->helloServer->stop();
	}
}