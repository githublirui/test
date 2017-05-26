<?php
/**
 * 云加速代理服务器，将真实ip 传递在x_forwarded_for 
 * 而yz-sao x_forwarded_for 被刷，使用x_gj_forwarded_for
 *  m6未涉及
 * @author    wangjian
 * @since     2014-7-15
 * @category  wap
 * @package   WapHttpNamespace.class.php
 * @version   0.1.0
 * @copyright Copyright (c) 2005-2014 GanJi Inc. (http://www.ganji.com)
 */
require_once dirname(__FILE__)  . '/../../config/config.inc.php';
require_once CODE_BASE2.'/util/http/HttpNamespace.class.php';
class  WapHttpNamespace extends HttpNamespace{

	public static function getIp($useInt = true, $returnAll=false){
		$x_forwarded=self::_GetXForwaredIp();
		if($x_forwarded&&!self::_isPrivateIp($x_forwarded)){
			return self::_returnIp($x_forwarded, $useInt, $returnAll);
		}
		return parent::getIp($useInt, $returnAll);
	}
	/**
	 *亦庄机房得这边得部署，soa 中增加了 新得HTTP_X_GJ_FORWARDED_FOR
	 */
	private static	function _GetXForwaredIp(){
		//get x_for real
		$x_forwarded_for='';
		$host_name=$_SERVER['HOSTNAME'];
		if($host_name[0].$host_name[1]=='g1'){
			$x_forwarded_for=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}else{
			if(isset($_SERVER['HTTP_X_GJ_FORWARDED_FOR'])){
				$x_forwarded_for=$_SERVER['HTTP_X_GJ_FORWARDED_FOR'];
			}
		}
		if($x_forwarded_for){
			if(stripos($x_forwarded_for,',')){
				$ipInfo=explode(',', $x_forwarded_for);
				return $ipInfo[0];//10.199.194.18,192.168.115.1  ==>10.199.194.18
			}
			return $x_forwarded_for;
		}
		return false;

	}

	/**
	 * @brief 是否私有ip
	 * @param string $ip
	 * @return boolean true|是, false|不是
	 */
	private static function _isPrivateIp($ip) {
		//      私有地址排除应该是如下的地址段：
		//A类 10.0.0.0--10.255.255.255
		//B类 172.16.0.0--172.31.255.255
		//C类 192.168.0.0--192.168.255.255
		$privateIps = array(
				'127.',
				'10.',
				'192.168.',
				// B类
				'172.16.',
				'172.17.',
				'172.18.',
				'172.19.',
				'172.20.',
				'172.21.',
				'172.22.',
				'172.23.',
				'172.24.',
				'172.25.',
				'172.26.',
				'172.27.',
				'172.28.',
				'172.29.',
				'172.30.',
				'172.31.',
		);
		foreach ($privateIps as $rangeIp) {
			$len = strlen($rangeIp);
			if (substr($ip, 0, $len) == $rangeIp) {
				return true;
			}
		}
		return false;
	}
	private static function _returnIp($ip, $useInt, $returnAll) {
		if (!$ip) return false;
	
		$ips = preg_split("/[，, _]+/", $ip);
		if (!$returnAll) {
			$ip = $ips[count($ips)-1];
			return $useInt ? self::ip2long($ip) : $ip;
		}
	
		$ret = array();
		foreach ($ips as $ip) {
			$ret[] = $useInt ? self::ip2long($ip) : $ip;
		}
		return $ret;
	}
}