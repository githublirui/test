<?php
/**
 * @Copyright (c) 2014 Ganji Inc.
 * @file          /finagle-php/client/http/SF_SocketHttpRequet.php
 * @create        [2014-05-04]赵卫国
 * @lastupdate    [2014-05-05]赵卫国
 * @other
 *
 *
 * @brief 测试http方式的client请求
 */

require_once dirname(__FILE__) . '/../../../SF_Bootstrap.php';

require_once FINAGLE_BASE . '/client/http/SF_SocketHttpRequest.php';
require_once FINAGLE_BASE . '/builder/SF_ClientBuilder.php';
require_once FINAGLE_BASE . "/exception/SF_Exception.php";

/**
 * Class SF_HttpRequestTest
 *
 * @brief: 测试http请求类型client相关请求
 * @codecoverage:
 *
 *      finagle/client/SF_HttpClient.php
 *      finagle/client/SF_HttpClientFactory.php
 *      finagle/client/http/SF_SocketHttpRequest.php
 *
 */
class SF_HttpRequestTest extends PHPUnit_Framework_TestCase {
    public static $flag=0;

    private static $proc;


    public static  function setUpBeforeClass() {
        echo "===>  unit:[SF_HttpRequestTest]  started...\n";

        ini_set('apc.enable_cli', '1');
        date_default_timezone_set('Asia/Chongqing');
        if(self::$flag) {
            sleep(1);
        } else {
            // start python task
            $pipes = array();
            self::$proc = proc_open("python ". FINAGLE_BASE ."/test/lib/testsrv.py 43298", array(), $pipes );
            self::$flag=1;
            sleep(1);
        }

    }

    public static function tearDownAfterClass() {
        proc_terminate(self::$proc);
    }


    /**
     * @brief:  Get类方法测试
     */
    public function testGet() {
        $host = '127.0.0.1';
        $port = 43298;
        $timeout = 1000000*10;



        $builder = new SF_ClientBuilder();
        $builder->tcpConnectTimeout(new SF_Duration(1, SF_Duration::UNIT_SECONDS))
            ->timeout(new SF_Duration(1, SF_Duration::UNIT_SECONDS))
            ->clientFactory(new SF_HttpClientFactory())
            ->loadBalance(new SF_RandomLoadBalancerFactory())
            ->retries(3)
            ->destName("test.httpclient.normal")
            ->hosts(
                array(
                    array(
                        "host" => $host,
                        "port" => $port,
                        "weight" => 1
                    )
                )
            );


        $impl=$builder->build();

        $req = new SF_HttpRequest(SF_HttpRequest::SCHEMA_HTTP, SF_HttpRequest::HTTP_METHOD_GET, "/ok?a=1&b=2");
        $rtn = $impl->execute($req);


        $this->assertEquals( json_decode($rtn,true), array("a"=>1,"b"=>2));
        unset($impl); unset($builder);
    }



    // 用于测试新的SF_Http请求
    public function testPost() {

        $host = '127.0.0.1';
        $port = 43298;
        $timeout = 1000000*10;

        $builder = new SF_ClientBuilder();
        $builder->tcpConnectTimeout(new SF_Duration(1, SF_Duration::UNIT_SECONDS))
            ->clientFactory(new SF_HttpClientFactory())
            ->loadBalance(new SF_RandomLoadBalancerFactory())
            ->retries(3)
            ->destName("rta.counter.http")
            ->hosts(
                array(
                    array(
                        "host" => $host,
                        "port" => $port,
                        "weight" => 1
                    )
                )
            );

        $impl=$builder->build();

        $req = new SF_HttpRequest(SF_HttpRequest::SCHEMA_HTTP, SF_HttpRequest::HTTP_METHOD_POST, "/ok");
        // 【可选】可以使用setXXX方法来修改http的其他属性，如
        //$req->setAccept(SF_HttpRequest::HTTP_ACCEPT_JSON);  // 设定Accept为application/json

        $req->setClientType(SF_HttpRequest::HTTP_CLIENT_TYPE_SOCKET); // 设定客户端的请求类型
        $p = array("Arg1"=>100,"Arg2"=>200);

        $req->setPostStr(json_encode( $p ));  // 此为必须执行的函数

        $rtn = $impl->execute($req);

        $this->assertEquals(json_decode($rtn,true), $p);

    }


    /**
     * @brief:  Get类方法错误请求测试
     */
    public function testGetError() {
        $host = '127.0.0.1';
        $port = 43298;
        $timeout = 1000000*10;



        $builder = new SF_ClientBuilder();
        $builder->tcpConnectTimeout(new SF_Duration(1, SF_Duration::UNIT_SECONDS))
            ->clientFactory(new SF_HttpClientFactory())
            ->loadBalance(new SF_RandomLoadBalancerFactory())
            ->retries(3)
            ->destName("rta.counter.http")
            ->hosts(
                array(
                    array(
                        "host" => $host,
                        "port" => $port,
                        "weight" => 1
                    )
                )
            );


        $impl=$builder->build();

        $req = new SF_HttpRequest(SF_HttpRequest::SCHEMA_HTTP, SF_HttpRequest::HTTP_METHOD_GET, "/error?a=1&b=2");
        try {
            $rtn = $impl->execute($req);
            $this->assertTrue(false);
        }
        catch(SF_HttpStatusException $e) {
            $this->assertTrue(true);
        } catch(SF_Exception $e) {
            var_dump(get_class($e));
            $this->assertTrue(false);
        } catch(Exception $e) {
            $this->assertTrue(false);
        }

        unset($impl); unset($builder);
    }



    /**
     * @brief:  Get类方法错误请求测试
     */
    public function testPostError() {
        // @todo 这个需要改，400类型的error不应该重试
        $host = '127.0.0.1';
        $port = 43298;
        $timeout = 1000000*10;



        $builder = new SF_ClientBuilder();
        $builder->tcpConnectTimeout(new SF_Duration(1, SF_Duration::UNIT_SECONDS))
            ->clientFactory(new SF_HttpClientFactory())
            ->loadBalance(new SF_RandomLoadBalancerFactory())
            ->retries(3)
            ->destName("rta.counter.http")
            ->hosts(
                array(
                    array(
                        "host" => $host,
                        "port" => $port,
                        "weight" => 1
                    )
                )
            );


        $impl=$builder->build();

        $req = new SF_HttpRequest(SF_HttpRequest::SCHEMA_HTTP, SF_HttpRequest::HTTP_METHOD_POST, "/error");
        $p = array("Arg1"=>100,"Arg2"=>200);
        $req->setPostStr(json_encode( $p ));  // 此为必须执行的函数

        try {
            $rtn = $impl->execute($req);
            $this->assertTrue(false);
        }
        catch(SF_HttpStatusException $e) {
            $this->assertTrue(true);
        } catch(SF_Exception $e) {
            $this->handleError($e);
            $this->assertTrue(false);
        } catch(Exception $e) {
            $this->handleError($e);
            $this->assertTrue(false);
        }

        unset($impl); unset($builder);
    }


    public function handleError($e) {
        echo "[Error handle]: Msg:[".get_class($e)."]=>[".$e->getMessage()."]\n";
    }


}


