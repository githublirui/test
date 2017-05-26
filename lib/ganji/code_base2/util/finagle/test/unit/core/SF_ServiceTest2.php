<?php
require_once dirname(__FILE__) . '/../../../SF_Bootstrap.php';

class FakeClient {
    private static $no = 0;
    public function __construct($host, $port, $timeout) {
        $this->host = $host;
        $this->port = $port;
        $this->timeout = $timeout;
        $this->id = FakeClient::$no ++;
        $this->error = false;
    }
    public function getId() {
        if( $this->error )
            throw new Exception("force error");
        return $this->id;
    }
    public function setError($error) {
        $this->error = $error;
    }
    public function close() {
    }
}

class FakeClientFactory {
    public function getClient($builder, $host, $port) {
        return new FakeClient($host, $port, 0);
    }
}

class SF_ServiceTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        $this->serviceName="test";
        $nodeStates = new SF_NodeStates();
        $nodeStates->getCreateTime(microtime());
        for( $i = 80;$i<90;$i++) {
            $stateModel = new SF_NodeState();
            $stateModel->host("127.0.0.1");
            $stateModel->port($i);
            $nodeStates->setNodeState($stateModel->key(), $stateModel);
        }

        SF_ServiceState::setStates($this->serviceName, $nodeStates);
    }

    public function testMethodInvoke() {

        $builder = new SF_ClientBuilder();
        $builder->tcpConnectTimeout(new SF_Duration(10, SF_Duration::UNIT_SECONDS));
        $builder->timeout(new SF_Duration(10, SF_Duration::UNIT_MILLISECONDS));
        $builder->clientFactory(new FakeClientFactory() );
        $builder->loadBalance(new SF_RandomLoadBalancerFactory());
        $builder->retries(3);
        $builder->destName($this->serviceName);
        $service=$builder->build();

        // 调用2次，应该返回同一个值
        $id1 = $service->getId();
        $id2 = $service->getId();
        var_dump($id1);
        var_dump($id2);
        
        $this->assertEquals( $id1, $id2 );

        // 当前的服务出现问题时，会自动选择另外一个
        $service->setError(true); // for the client fail
        try{
	        $id3 = $service->getId();
        }catch(Exception $e) {
        	$this->assertEquals($e->getMessage() , "force error");
        }
        //$this->assertNotEquals( $id1, $id3 );
    }


    public function tearDown() {

    }
}