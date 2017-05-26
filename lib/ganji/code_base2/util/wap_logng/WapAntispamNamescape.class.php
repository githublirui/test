<?php
if(defined('CODE_EBSE2')){
	require_once CODE_BASE2.'/util/cache/CacheNamespace.class.php';
}else{
	require_once dirname(__FILE__).'/../cache/CacheNamespace.class.php';
}
/**
 * Enter description here ...
 *
 * @author    wangjian
 * @since     2014-8-8
 * @category  wap
 * @package   WapAntispamNamescape.class.php
 * @version   0.1.0
 * @copyright Copyright (c) 2005-2014 GanJi Inc. (http://www.ganji.com)
 */
class WapAntispamNamescape {
	private static function GetMecConfGroup(){
		// 		测试环境MC：
		// 		192.168.2.183:21211
		// 		192.168.2.183:21212
		// 		192.168.2.183:21213
		// 		192.168.2.183:21214
		// 		192.168.2.183:21215

		$server[]=array(
				"host" => "192.168.2.183",
				"port" => "21211",
				"weight" => 1
		);
		$server[]=array(
				"host" => "192.168.2.183",
				"port" => "21212",
				"weight" => 1
		);
		$server[]=array(
				"host" => "192.168.2.183",
				"port" => "21213",
				"weight" => 1
		);
		$server[]=array(
				"host" => "192.168.2.183",
				"port" => "21214",
				"weight" => 1
		);
		$server[]=array(
				"host" => "192.168.2.183",
				"port" => "21215",
				"weight" => 1
		);
		return $server;
	}
	//7d
	const EXPIRE_TIME=25200;
	static $CACHE_KEY="_WAP_SPIDER_UUID_";
	
	public static function SetSpiderMec($uuid){
		if(!$uuid){
			return false;
		}
		$key=self::$CACHE_KEY.$uuid;
		$cache = CacheNamespace::createCache(CacheNamespace::MODE_MEMCACHE,self::GetMecConfGroup());
		$cache->write($key, $uuid,self::EXPIRE_TIME);
	}

}