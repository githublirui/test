<?php

require_once dirname(__FILE__) . '/../../../SF_Bootstrap.php';


require_once FINAGLE_BASE . '/builder/SF_ClientBuilder.php';
require_once FINAGLE_BASE . '/exception/SF_Exception.php';


$GEN_DIR = realpath(dirname(__FILE__).'/../..').'/lib/gen-php';
require_once $GEN_DIR.'/shared/SharedService.php';
require_once $GEN_DIR.'/shared/shared_types.php';
require_once $GEN_DIR.'/tutorial/Calculator.php';
require_once $GEN_DIR.'/tutorial/tutorial_types.php';
error_reporting(E_ALL);

/**
 * Class SF_THttpClientTest
 * @brief 测试thrift类型http请求
 * @todo 需要修改
 * @codecoverage:
 *
 *      finagle/client/SF_THttpClient.php
 *      finagle/client/SF_THttpClientFactory.php
 *
 */
class SF_THttpClientTest extends  PHPUnit_Framework_TestCase {
    public static $flag=0;

    private static $proc;
    private static $host;
    private static $port;
    private static $path;

    public static function setUpBeforeClass() {
        //server 命令
        // java -jar -Dservice.host=127.0.0.1 -Dservice.port=18080 -Dservice.announce=zk!10.3.255.222:2181
        //!/soa/services/hello!1 finagle.service.deploy-1.0-jar-with-dependencies.jar

        echo "thrift http client test starting...";

        ini_set('apc.enable_cli', '1');
        date_default_timezone_set('Asia/Chongqing');


        self::$host="127.0.0.1";
        self::$port=43298;
        self::$path = "/php/SF_THttpServerTest.php";


        if(self::$flag) {
            sleep(1);
        } else {
            // start python task
            $pipes = array();
            self::$proc = proc_open("python ". FINAGLE_BASE ."/test/lib/php/SF_THttpServerTest.py 43298", array(), $pipes );
            self::$flag=1;
            sleep(1);
        }
    }

    public  function testSimple() {
        echo FINAGLE_BASE;

        $builder = new SF_ClientBuilder();
        $builder->tcpConnectTimeout(new SF_Duration(10, SF_Duration::UNIT_MILLISECONDS))
            ->clientFactory(new SF_THttpClientFactory("CalculatorClient", self::$path))
            ->loadBalance(new SF_RandomLoadBalancerFactory())
            ->retries(3)
            ->destName("test.thrift_http")
            ->hosts(
                array(
                    array(
                        "host" => self::$host,
                        "port" => self::$port,
                        "weight" => 1
                    )
                )
            );

        $impl=$builder->build();

        try{
            $result = $impl->add(1, 2);
            echo $result;
        }
        catch (Exception $e) {
            throw $e;
        }


        $this->assertEquals( 1, 2);
        unset($impl); unset($builder);


    }

    public static function tearDownAfterClass() {
        proc_terminate(self::$proc);
    }
}