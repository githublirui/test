<?php
ini_set('display_errors', 0);
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
/**
 * Enter description here ...
 *
 * @author    wangjian
 * @touch     2013-7-3
 * @category  wap
 * @package   logng.test.php
 * @version   0.1.0
 * @copyright Copyright (c) 2005-2013 GanJi Inc. (http://www.ganji.com)
 */
require_once dirname(__FILE__).'/../../../config/config.inc.php';
require_once dirname(__FILE__).'/../WapLogngNamespace.class.php';
require_once CODE_BASE2.'/util/http/HttpNamespace.class.php';

function test_getOrganicInfo(){
	//moc
// 	$_GET['ca_source']='1';$_GET['ca_name']='2';
// 	$_GET['ca_id']='33';$_GET['ca_kw']='4';
session_id('ec60af044d826dc882bfdfe9370d73ef');
	$_SERVER['HTTP_REFERER']="http://ww.ebaidu.com/dasdasd/dasdas";
	//run
	$s=WapLogngNamespace::getOrganicInfo();
	var_dump(urldecode($s));
// 	var_dump(session_id());
	$info=SessionNamespace::getValue(WapLogngNamespace::CA_INFO_SESSION_KEY);
	
	var_dump($info);
}
function test_getOrganicInfoV2(){
	// $_SERVER['HTTP_REFERER']="http://3g.ganji.com/dsadasdasd";
	// $_GET['from']="sem";$_REQUEST['from']="sem";
	//=============> ca_name=sem ca_source=www.baidu.com
	
	// $_SERVER['HTTP_REFERER']="http://www.baidu.com/dsadasdasd";
	// $_GET['from']="sem";$_REQUEST['from']="sem";
	// //=============> ca_name=sem ca_source=www.baidu.com
	
	//**********************
	//$_GET['from']="sem";$_REQUEST['from']="sem";
	//=============> ca_name=sem ca_source=-
	//**********************/
	
	// $_SERVER['HTTP_REFERER']="http://www.baidu.com/dsadasdasd";
	// $_GET['ca_source']="sem";$_REQUEST['ca_source']="sem";
	// $_GET['ca_name']="semca_name";$_REQUEST['ca_name']="semca_name";
	// // 	//				====>  ca_source=sem ca_name=semca_name
	/************/
	// $_SERVER['HTTP_REFERER']="http://www.baidu.com/dsadasdasd";
	// $_GET['ca_source']="sem";$_REQUEST['ca_source']="sem";
	// $_GET['ca_name']="semca_name";$_REQUEST['ca_name']="semca_name";
	// $_GET['ca_kw']="semca_name";$_REQUEST['ca_kw']="semca_name";
	// $_GET['ca_id']="semca_name";$_REQUEST['ca_id']="semca_name";
	// // 	//				====>  array(4) { ["ca_name"]=> string(10) "semca_name"
	// //["ca_source"]=> string(3) "sem" ["ca_id"]=> string(10) "semca_name" ["ca_kw"]=> string(10) "semca_name" }
	/************/
	// $_GET['ca_name']="qudao";
	//***************/
	// 	$_SERVER['HTTP_REFERER']="http://www.baidu.com/dsadasdasd";
	// 	// $_GET['ca_source']="sem";$_REQUEST['ca_source']="sem";
	// 	$_GET['ca_name']="semca_name";$_REQUEST['ca_name']="semca_name";
	// 	//				====>  ca_source=www.baidu.com ca_name=semca_name
	//******************/
	// 	$_SERVER['HTTP_REFERER']="http://wap.ganji.com/dsadasdasd";
	// 	// $_GET['ca_source']="sem";$_REQUEST['ca_source']="sem";
	// 	$_GET['ca_name']="semca_name";$_REQUEST['ca_name']="semca_name";
	// 	//				====>  ca_source=- ca_name=semca_name
	//********************/
	// 	$_SERVER['HTTP_REFERER']="http://bj.ganji.com/dsadasdasd/";
	// 	// $_GET['ca_source']="sem";$_REQUEST['ca_source']="sem";
	// 	$_GET['ca_name']="zhuzhan2wap";$_REQUEST['ca_name']="zhuzhan2wap";
	// 	//				====>  ca_source=- ca_name=zhuzhan2wap
	
	
	
	
}


test_getOrganicInfo();