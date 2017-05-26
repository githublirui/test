<?php
require_once dirname(__FILE__) . '/../../../SF_Bootstrap.php';

require_once FINAGLE_BASE . "/test/lib/hellodemo/hello_types.php";
require_once FINAGLE_BASE . "/test/lib/hellodemo/Hello.php";
require_once FINAGLE_BASE . "/test/lib/hellodemo/ThriftHelloServer.php";

/**
 * Class SF_ThriftClientTest
 * @brief 测试thrift类型请求
 * @codecoverage:
 *
 *      finagle/client/SF_ThriftClient.php
 *      finagle/client/SF_ThriftClientFactory.php
 *
 */
class SF_ThriftClientTest extends  PHPUnit_Framework_TestCase {

    private static $host;
    private static $port;
    private static $serviceName;
    private static $helloServer;


    public static  function setUpBeforeClass() {
        echo "[unit start]". __CLASS__ ." \n";
        self::$host = "192.168.35.141";
        self::$port=8888;
        self::$serviceName="/soa/services/hello";
        /*
		//server 命令
		// java -jar -Dservice.host=127.0.0.1 -Dservice.port=18080 -Dservice.announce=zk!10.3.255.222:2181
		//!/soa/services/hello!1 finagle.service.deploy-1.0-jar-with-dependencies.jar
		self::$host="127.0.0.1";
		self::$port=8888;
		$serviceName="/soa/services/hello";
		self::$helloServer = new ThriftHelloServer();
		self::$helloServer->start($serviceName, self::$host, self::$port);
		sleep(10);
        */
	}
	
	public  function testHi() {
		$builder = new SF_ClientBuilder();
		$builder->tcpConnectTimeout(new SF_Duration(10, SF_Duration::UNIT_MILLISECONDS));
        $builder->build();
		$clientFactory = new SF_ThriftClientFactory("HelloClient",true);
		$client = $clientFactory->getClient($builder,self::$host, self::$port);
		$result = $client->hi("kaka");
		$this->assertEquals("hi,kaka",$result);

        $clientFactory = new SF_ThriftClientFactory("HelloClient",false);
        $client = $clientFactory->getClient($builder,self::$host, self::$port);
        $result = $client->hi("kaka");
        $this->assertEquals("hi,kaka",$result);

    }
	
	public static function tearDownAfterClass() {
		//self::$helloServer->stop();
	}
}