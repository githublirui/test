<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   yangyu$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2011, www.ganji.com
 */

require_once dirname(__FILE__) . "/../Curl.class.php";
class CurlTest  extends PHPUnit_Framework_TestCase{
	/** setUp
     */
	protected function setUp(){
        $this->curl = new Curl();
    }
	/** tearDown
     */
	protected function tearDown(){
        $this->curl = null;
    }
	/** testLogin
     */
	public function testLogin(){
        //your test code write here!
        $url  = 'http://www.ganji.com/user/login.php';
        $post['next'] = '';
        $post['no_cookie_test'] = 1;
        $post['email']  = 'xlff858@hotmail.com';
        $post['password']   = '112233';
        $html = $this->curl->login($url,$post);
        $this->assertContains('success',$html);

        $get['do'] = 'login';
        $url = 'http://tuiguang.ganji.com/auth.php';
        $html = $this->curl->login($url,$get,'get');
        $this->assertContains('赶集信息推广中心', $html);

        $url = 'http://tuiguang.ganji.com/auth.php?';
        $html = $this->curl->login($url,$get,'get');
        $this->assertContains('赶集信息推广中心', $html);
    }
	/** testSetIsOutputHeader
     */
	public function testSetIsOutputHeader(){
        //your test code write here!
        $ok = $this->curl->setIsOutputHeader(true);
        $this->isTrue($ok);
        $ok = $this->curl->setIsOutputHeader('abc');
        $this->isFalse($ok);
    }
	/** testSetIsNoBody
     */
	public function testSetIsNoBody(){
        //your test code write here!
        $ok = $this->curl->setIsNoBody(true);
        $this->isTrue($ok);
        $ok = $this->curl->setIsNoBody('abc');
        $this->isFalse($ok);
    }
	/** testSetTimeout
     */
	public function testSetTimeout(){
        //your test code write here!
        $ok = $this->curl->setTimeout(3);
        $this->isTrue($ok);
        $ok = $this->curl->setTimeout('abc');
        $this->isFalse($ok);
    }
	/** testSetReferer
     */
	public function testSetReferer(){
        //your test code write here!
        $refresh  = 'http://www.ganji.com/user/login.php';
        $ok = $this->curl->setReferer($refresh);
        $this->isTrue($ok);
        $ok = $this->curl->setReferer(1);
        $this->isFalse($ok);
    }
	/** testVerbose
     */
	public function testVerbose(){
        //your test code write here!
        $ok = $this->curl->setVerbose(true);
        $this->isTrue($ok);
        $ok = $this->curl->setVerbose(1);
        $this->isFalse($ok);
    }
	/** testPost
     */
	public function testPost(){
        //your test code write here!
        $url  = 'http://www.ganji.com/user/login.php';
        $html = $this->curl->post($url,false);
        $this->isFalse($html);
    }
	/** testGet
     */
	public function testGet(){
        //your test code write here!
        $this->isTrue(true);
    }
	/** testDownload
     */
	public function testDownload(){
        //your test code write here!
        $url = "http://tuiguang-2010.local.ganji.com/i.php";
        $filename = $this->curl->download($url);
        $this->isTrue(is_file($filename));

        $filename = $this->curl->download('');
        $this->isFalse($filename);

        $filename = $this->curl->download($url, '/tmp/abc/sss');
        $this->isFalse($filename);
    }
	/** testDataEncode
     */
	public function testDataEncode(){
        //your test code write here!
        $this->isTrue();
    }
	/** testGetHttpCode
     */
	public function testGetHttpCode(){
        //your test code write here!
        $url = 'http://bj.ganji.com';
        $code = $this->curl->getHttpCode($url);
        $this->assertEquals($code,200);
    }
	/** testLog
     */
	public function testLog(){
        //your test code write here!
        $this->isTrue(true);
    }
}
