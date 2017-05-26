<?php
require_once dirname(__FILE__) . '/../../../SF_Bootstrap.php';
require_once FINAGLE_BASE . '/unittest/Hello.php';
require_once FINAGLE_BASE . '/unittest/hello_types.php';
class SF_ServiceTest extends PHPUnit_Framework_TestCase {
	
	public function setUp() {
		$this->serviceName="/soa/services/hello";
	}
	
	public function testMethodInvoke() {
		
		$clientStub = $this->getMockBuilder('SF_ThriftClient')
										->disableOriginalConstructor() 
										->getMock();
		
		$clientStub->expects($this->any())
							->method("__call")
							->will($this->returnValue("hi,kaka"));
		
		$clientFactoryStub = $this->getMockBuilder('SF_ThriftClientFactory')
										->setConstructorArgs(array("HelloClient"))
										->getMock();
		
		$clientFactoryStub->expects($this->any())
									   ->method("getClient")
									   ->will($this->returnValue($clientStub));
		
		$nodeStates = new SF_NodeStates();
		$stateModel1 = new SF_NodeState();
		$stateModel1->host("127.0.0.1");
		$stateModel1->port(80);
		$nodeStates->setNodeState($stateModel1->key(), $stateModel1);
		SF_ServiceState::setStates($this->serviceName, $nodeStates);
		
		$builder = new SF_ClientBuilder();
		$builder->tcpConnectTimeout(new SF_Duration(10, SF_Duration::UNIT_SECONDS));
		$builder->timeout(new SF_Duration(10, SF_Duration::UNIT_MILLISECONDS));
		$builder->clientFactory($clientFactoryStub);
// 		$builder->clientFactory( new SF_ThriftClientFactory("HelloClient"));
		$builder->loadBalance(new SF_RandomLoadBalancerFactory());
		$builder->retries(3);
		$builder->destName($this->serviceName);
		$service=$builder->build();
		$result = $service->hi("kaka");
		var_dump($result);
	}
	
	public function testRetry() {
		$retryTimes = 3;
		$clientStub = $this->getMockBuilder('SF_ThriftClient')
										->disableOriginalConstructor()
										->getMock();
		
		$clientStub->expects($this->exactly($retryTimes))
		->method("__call")
		->will($this->throwException(new SF_SocketException("test")));
		
		$clientFactoryStub = $this->getMockBuilder('SF_ThriftClientFactory')
		->setConstructorArgs(array("HelloClient"))
		->getMock();
		
		$clientFactoryStub->expects($this->any())
		->method("getClient")
		->will($this->returnValue($clientStub));
		
		$nodeStates = new SF_NodeStates();
		$stateModel1 = new SF_NodeState();
		$stateModel1->host("127.0.0.1");
		$stateModel1->port(80);
		$nodeStates->setNodeState($stateModel1->key(), $stateModel1);
		SF_ServiceState::setStates($this->serviceName, $nodeStates);
		
		$builder = new SF_ClientBuilder();
		$builder->tcpConnectTimeout(new SF_Duration(10, SF_Duration::UNIT_SECONDS));
		$builder->timeout(new SF_Duration(10, SF_Duration::UNIT_MILLISECONDS));
		$builder->clientFactory($clientFactoryStub);
		// 		$builder->clientFactory( new SF_ThriftClientFactory("HelloClient"));
		$builder->loadBalance(new SF_RandomLoadBalancerFactory());
		$builder->retries($retryTimes);
		$builder->destName($this->serviceName);
		$service=$builder->build();
		try{
			$result = $service->hi("kaka");
		}catch (Exception $e) {
// 			var_dump($e);
		}
// 		var_dump($result);
	}
	
	public function tearDown() {
		
	}
}