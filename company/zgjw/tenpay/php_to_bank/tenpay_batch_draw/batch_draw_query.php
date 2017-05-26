<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=gbk">
	<title>财付通批量银行代付接口程序演示</title>
</head>
<body>

<?php
require_once ("classes/client/TenpayHttpClient.class.php");
require_once ("classes/client/DirectTransClientRequestHandler.class.php");
require_once ("classes/client/DirectTransClientResponseHandler.class.php");

//密钥
$key = "82d2f8b9fd7695aec51415ab2900a755";
/* 创建支付请求对象 */
$reqHandler = new DirectTransClientRequestHandler();
//通信对象
$httpClient = new TenpayHttpClient();
//应答对象
$resHandler = new DirectTransClientResponseHandler();

//-----------------------------
//设置请求参数
//-----------------------------
$reqHandler->init();
$reqHandler->setKey($key);

//设置方法一：逐个参数设置
$reqHandler->setParameter("op_code", "1014");			
$reqHandler->setParameter("service_version", "1.1");					
$reqHandler->setParameter("op_name", "batch_draw_query");	
$reqHandler->setParameter("op_user", "1900000107");	
$reqHandler->setParameter("op_passwd", "111111");	
$reqHandler->setParameter("op_time", "20110823105511000");
$reqHandler->setParameter("sp_id", "1900000107");	
$reqHandler->setParameter("package_id", "20110823003galen");
$reqHandler->setParameter("client_ip", "119.147.78.6");



//设置方法二：通过XML设置参数
/*
$xml = "<?xml version=\"1.0\" encoding=\"GB2312\" ?>
<root>
	<op_code>1014</op_code>
	<op_name>batch_draw_query</op_name>
	<op_user>1900000107</op_user>
	<op_passwd>111111</op_passwd>
	<op_time>20110823105511000</op_time>
	<sp_id>1900000107</sp_id>
	<package_id>20110823001</package_id>
	<client_ip>10.6.35.42</client_ip>
</root>";
$reqHandler->setAllParameterFromXml($xml);
*/

//-----------------------------
//设置通信参数
//-----------------------------
//设置PEM证书，pfx证书转pem方法：openssl pkcs12 -in 1900000107.pfx  -out 1900000107.pem
//证书必须放在用户下载不到的目录，避免证书被盗取
$httpClient->setCertInfo("e:/1900000107.pem", "1900000107");
//设置CA
$httpClient->setCaInfo("e:/cacert.pem");
//设置请求内容
$httpClient->setReqContent($reqHandler->getRequestURL());
//设置超时
$httpClient->setTimeOut(20);

if($httpClient->call()) {
	//取结果参数做业务处理
	$resHandler->setContent($httpClient->getResContent());
	$resHandler->setKey($key);
	
	if($resHandler->getParameter("retcode")== "0" || $resHandler->getParameter("retcode") == "00") {
		//取结果参数做业务处理
		echo "OK,package_id=" . $resHandler->getParameter("package_id") . "<br>";
		
		
	} else {
		//错误时，返回结果未签名。
		//如包格式错误或未确认结果的，请使用原来批次号重新发起，确认结果，避免多次操作
		echo "业务错误信息:" . $resHandler->getParameter("retcode") . "," . $resHandler->getParameter("retmsg") . "<br>";
	}
	
	
} else {
	//后台调用通信失败
	echo "call err:" . $httpClient->getErrInfo() . "<br>";
	//有可能因为网络原因，请求已经处理，但未收到应答。
}


//调试信息

echo "<br>------------------------------------------------------<br>";
echo "req:" . htmlentities($reqHandler->getRequestURL(), ENT_NOQUOTES, "GB2312") . "<br><br>";
echo "res:" . htmlentities($resHandler->getContent(), ENT_NOQUOTES, "GB2312") . "<br><br>";
echo "reqdebug:" . htmlentities($reqHandler->getDebugInfo(), ENT_NOQUOTES, "GB2312") . "<br><br>" ;



?>


</body>
</html>