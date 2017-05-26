<?php 
require_once dirname(__FILE__).'/bootstrap.php';
require_once CODE_BASE2 . '/util/http/WapHttpNamespace.class.php';

/**
 * @brief http分析与跳转的测试
 * @author    duxiang duxiang@ganji.com
 * @copyright (c) 2013 Ganji Inc.
 * @date      时间: 2013-8-8:下午09:26:57
 * @version   1.0
 */
class HttpNamespaceTest /*extends PHPUnit_Framework_TestCase */{

    /**
     * @dataProvider ipData
     */
    public function testgetIp($assertIp = '', $REMOTE_ADDR = '',$HTTP_GJ_CLIENT_IP = '', $HTTP_X_FORWARDED_FOR = '',$HTTP_X_GJ_FORWARDED_FOR='') {
    	$_SERVER['HOSTNAME']       = 'yz-mob-wap-05';
    	//$_SERVER['HOSTNAME']       = 'g1-mob-wap-05';
    	$_SERVER['REMOTE_ADDR']       = $REMOTE_ADDR;
        $_SERVER['HTTP_GJ_CLIENT_IP'] = $HTTP_GJ_CLIENT_IP;
        $_SERVER['HTTP_X_FORWARDED_FOR'] = $HTTP_X_FORWARDED_FOR;
        @putenv("REMOTE_ADDR={$REMOTE_ADDR}");
        @putenv("HTTP_GJ_CLIENT_IP={$HTTP_GJ_CLIENT_IP}");
        @putenv("HTTP_X_FORWARDED_FOR={$HTTP_X_FORWARDED_FOR}");
        if(stripos($_SERVER['HOSTNAME'],'yz')!==false){
	        $_SERVER['HTTP_X_GJ_FORWARDED_FOR'] = $HTTP_X_GJ_FORWARDED_FOR;
	        @putenv("HTTP_X_GJ_FORWARDED_FOR={$HTTP_X_GJ_FORWARDED_FOR}");
        }
        $ip = WapHttpNamespace::getIp(false);
		print "<br>";
        var_dump($assertIp, $ip);
    }

    public function ipData() {
        $ret = array(
        		//	$assertIp , $REMOTE_ADDR ,$HTTP_GJ_CLIENT_IP , $HTTP_X_FORWARDED_FOR ,$HTTP_X_GJ_FORWARDED_FOR
    			//全是外网或者没有
    			array('59.83.177.103', '59.83.177.100', '59.83.177.101', '192.168.115.1','59.83.177.103,192.168.115.1'),
    			array('59.83.177.101', '', '59.83.177.101','192.168.115.1', '192.168.115.1'),
    			array('59.83.177.102', '', '','192.168.115.1', '59.83.177.102,192.168.115.1'),
    
    			// REMOTE_ADDR 内网
    			array('59.83.177.102', '192.168.1.100', '59.83.177.101','192.168.115.1', '59.83.177.102,192.168.115.1'),
    			array('59.83.177.102', '192.168.1.100', '59.83.177.102','192.168.115.1', '192.168.115.1'),
    			array('192.168.1.100', '192.168.1.100', '','192.168.115.1', '192.168.115.1'),
    
    			// REMOTE_ADDR 内网, 但是其他的都没有
    			array('~', '192.168.1.100', '', '',''),
    
    			// REMOTE_ADDR 内网B类, 但是其他的都没有
    			array('59.83.177.102', '172.19.0.0', '59.83.177.101','192.168.115.1', '59.83.177.102,192.168.115.1'),
    			array('59.83.177.102', '172.19.0.0', '59.83.177.102','192.168.115.1', '192.168.115.1'),
    
    			// REMOTE_ADDR 外网172.59.xx
    			array('59.83.177.102', '172.59.1.1', '59.83.177.101','192.168.115.1', '59.83.177.102,192.168.115.1'),
    			array('59.83.177.101', '172.59.1.1', '59.83.177.101','192.168.115.1', '192.168.115.1'),
        );
        return $ret;
    }
    
    public function ipData_m6() {
    	$ret = array(
    			//	$assertIp , $REMOTE_ADDR ,$HTTP_GJ_CLIENT_IP , $HTTP_X_FORWARDED_FOR ,$HTTP_X_GJ_FORWARDED_FOR
    			//全是外网或者没有
    			array('59.83.177.101', '59.83.177.100', '59.83.177.101', '192.168.115.1','59.83.177.103,192.168.115.1'),
    			array('59.83.177.101', '', '59.83.177.101','192.168.115.1', '59.83.177.102,192.168.115.1'),
    			array('192.168.115.1', '', '','192.168.115.1', '59.83.177.102,192.168.115.1'),
    
    			// REMOTE_ADDR 内网
    			array('59.83.177.101', '192.168.1.100', '59.83.177.101','192.168.115.1', '59.83.177.102,192.168.115.1'),
    			array('59.83.177.102', '192.168.1.100', '59.83.177.102','192.168.115.1', '192.168.115.1'),
    			array('192.168.115.1', '192.168.1.100', '','192.168.115.1', '59.83.177.102,192.168.115.1'),
    
    			// REMOTE_ADDR 内网, 但是其他的都没有
    			array('192.168.1.100', '192.168.1.100', '', '',''),
    
    			// REMOTE_ADDR 内网B类, 但是其他的都没有
    			array('59.83.177.101', '172.19.0.0', '59.83.177.101','192.168.115.1', '59.83.177.102,192.168.115.1'),
    			array('59.83.177.102', '172.19.0.0', '59.83.177.102','192.168.115.1', '192.168.115.1'),
    
    			// REMOTE_ADDR 外网172.59.xx
    			array('59.83.177.101', '172.59.1.1', '59.83.177.101','192.168.115.1', '59.83.177.102,192.168.115.1'),
    			array('59.83.177.101', '172.59.1.1', '59.83.177.101','192.168.115.1', '192.168.115.1'),
    	);
    	return $ret;
    }
}

$c=0;
foreach(HttpNamespaceTest::ipData() as $item){
//foreach(HttpNamespaceTest::ipData_m6() as $item){
	$c++;
	echo $c;
	$v=HttpNamespaceTest::testgetIp($item[0],$item[1],$item[2],$item[3],$item[4]);
	
}
