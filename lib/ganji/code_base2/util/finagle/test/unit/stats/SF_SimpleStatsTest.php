<?php

/**
 * @Copyright (c) 2014 Ganji Inc.
 * @file          /finagle-php/test/unit/stats/SF_SimpleStatsTest.php
 * @create        [2014-05-13]赵卫国
 * @lastupdate    [2014-05-20]赵卫国
 * @other
 *
 *
 * @brief 测试client请求的stats收集功能
 * @codecoverage:
 *
 *      finagle/stats/SF_SimpleStats.php
 *      finagle/stats/SF_SimpleStatsFactory.php
 *
 */

require_once dirname(__FILE__) . '/../../../SF_Bootstrap.php';

require_once FINAGLE_BASE . '/client/http/SF_SocketHttpRequest.php';
require_once FINAGLE_BASE . '/builder/SF_ClientBuilder.php';
require_once FINAGLE_BASE . '/exception/SF_Exception.php';

require_once FINAGLE_BASE . "/stats/SF_SimpleStats.php";
require_once FINAGLE_BASE . "/stats/SF_SimpleStatsFactory.php";

class SF_SimpleStatsTest extends PHPUnit_Framework_TestCase {
    public static $flag=0;

    protected function setUp() {
        ini_set('apc.enable_cli', '1');
        date_default_timezone_set('Asia/Chongqing');
        if(self::$flag) {
            sleep(1);
        } else {
            // start python task
            $pipes = array();
            $this->proc = proc_open("python ". FINAGLE_BASE ."/test/lib/testsrv.py 43298", array(), $pipes );
            self::$flag=1;
            sleep(1);
        }

    }


    public function testStats() {
        $count = 10000;
        for($i=0; $i<$count; $i++) {
            $this->statsUnit();
            //usleep(1000*100);
        }
    }


    /**
     * @brief:  Get类方法测试
     */
    public function statsUnit() {
        $host = '127.0.0.1';
        $port = 43298;

        $builder = new SF_ClientBuilder();
        $builder->tcpConnectTimeout(new SF_Duration(1, SF_Duration::UNIT_SECONDS))
            ->clientFactory(new SF_HttpClientFactory())
            ->loadBalance(new SF_RandomLoadBalancerFactory())
            ->retries(3)
            ->destName("test.httpclient.stats")
            ->stats(SF_SimpleStats_Factory::get(0.1))
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









}