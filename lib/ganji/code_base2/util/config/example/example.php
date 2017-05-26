<?php
/*
 * Created on 2012-12-4
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 $current_dir = pathinfo(__FILE__);
 require_once($current_dir['dirname']."/../ConfigNamespace.class.php");
 require_once($current_dir['dirname']."/../../ganji_conf/GlobalConfig.class.php");
 class ReflectTest {
 	public static $static1 = "<ganji_image_server>";
 	/**
 	 * @config("ganji_image_server")
 	 */
 	public static $static2 = "memcache_server";
 	
 	const C_3 = 10;
 	
 	public $var_4 = 200;
 }
 
 $config_name_list = array("ms","ms.yz");
 ConfigNamespace::init(GlobalConfig::$GANJI_CONFIG_FILE_DIR,$config_name_list);
 ConfigNamespace::initNotedFields(ReflectTest);
 echo ReflectTest::$static2."\n";
?>
