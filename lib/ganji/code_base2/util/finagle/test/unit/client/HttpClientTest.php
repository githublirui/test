<?php
require_once dirname(__FILE__) . '/../../../SF_Bootstrap.php';

require_once FINAGLE_BASE . '/client/SF_HttpRequest.php';

class HttpClientTest extends PHPUnit_Framework_TestCase
{
    public function setUp() {
        // start python task
        $pipes = array();
        $this->proc = proc_open("python ".FINAGLE_BASE . "/test/lib/testsrv.py 43298", array(),$pipes );
        sleep(1);
        // 设定host, port, timeout and retry
        $this->cli = new SF_HttpClient( "127.0.0.1", "43298", SF_Duration::fromSeconds(10));
        $this->cli->connect();
    }

    public function tearDown() {
        proc_terminate( $this->proc );
    }

    // 用于测试新的SF_Http请求
    public function testNewGet() {
        $req = new SF_HttpRequest(SF_HttpRequest::SCHEMA_HTTP, SF_HttpRequest::HTTP_METHOD_GET, "/ok?a=1&b=2");
        // 【可选】可以使用setXXX方法来修改http的其他属性，如
        //$req->setAccept(SF_HttpRequest::HTTP_ACCEPT_JSON);  // 设定Accept为application/json

        $req->setClientType(SF_HttpRequest::HTTP_CLIENT_TYPE_SOCKET); // 设定客户端的请求类型
        //现在只实现上面这种socket模式，未来可以增加下面这种
        //$req->setClientType(SF_HttpRequest::HTTP_CLIENT_TYPE_CURL);
        $this->cli->execute($req);
    }


    // 用于测试新的SF_Http请求
    public function testNewPost() {
        $req = new SF_HttpRequest(SF_HttpRequest::SCHEMA_HTTP, SF_HttpRequest::HTTP_METHOD_POST, "/ok");
        // 【可选】可以使用setXXX方法来修改http的其他属性，如
        //$req->setAccept(SF_HttpRequest::HTTP_ACCEPT_JSON);  // 设定Accept为application/json

        $req->setClientType(SF_HttpRequest::HTTP_CLIENT_TYPE_SOCKET); // 设定客户端的请求类型
        $p = array("Arg1"=>100,"Arg2"=>200);
        $req->setPostStr(json_encode( $p ));  // 此为必须执行的函数
        //现在只实现上面这种socket模式，未来可以增加下面这种
        //$req->setClientType(SF_HttpRequest::HTTP_CLIENT_TYPE_CURL);
        $this->cli->execute($req);
    }



/*

    public function testGet() {
        $req = new SF_HttpRequest(SF_HttpRequest::HTTP_VERSION_1_0, SF_HttpRequest::HTTP_METHOD_GET, "/ok?a=1&b=2");
        $r = $this->cli->execute( $req );
        $this->assertEquals( json_decode($r,true), array("a"=>1,"b"=>2));
    }

    public function testPost() {
        $p = array("Arg1"=>100,"Arg2"=>200);
        $req = new SF_HttpRequest(SF_HttpRequest::HTTP_VERSION_1_0, SF_HttpRequest::HTTP_METHOD_POST, "/ok");
        $req->setContent( json_encode( $p ));
        $r = $this->cli->execute($req);
        $r = json_decode( $r , true );
        $this->assertEquals($r, $p);
    }

    public function testGetError() {
        //TODO: get don't throw exception on error, FIX IT
        $req = new SF_HttpRequest(SF_HttpRequest::HTTP_VERSION_1_0, SF_HttpRequest::HTTP_METHOD_GET, "/error?a=1&b=2");
        try {
            $r = $this->cli->execute( $req );
            var_dump($r);
            $this->assertEquals($r,'');
        }
        catch(Exception $e) {
            $this->assertEquals(get_class($e),"SF_SocketException");
        }
    }

    public function testPostError() {
        $req = new SF_HttpRequest(SF_HttpRequest::HTTP_VERSION_1_0, SF_HttpRequest::HTTP_METHOD_POST, "/error?a=1&b=2");
        try {
            $r = $this->cli->execute( $req );
            $this->assertFail();
        }
        catch(Exception $e) {
            $this->assertTrue(is_a($e,"SF_SocketException"));
        }
    }
*/
}
