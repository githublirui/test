<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=gbk">
	<title>代付退款查询调用示例</title>
</head>
<body>

<?php
//---------------------------------------------------------
//财付通代付退款查询调用示例，商户按照此文档进行开发即可
//---------------------------------------------------------

require ("classes/RequestHandler.class.php");
require ("classes/client/ClientResponseHandler.class.php");
require ("classes/client/TenpayHttpClient.class.php");

/* 商户号 */
$partner = "1900000107";


/* 密钥 */
$key = "82d2f8b9fd7695aec51415ab2900a755";




/* 创建支付请求对象 */
$reqHandler = new RequestHandler();

//通信对象
$httpClient = new TenpayHttpClient();

//应答对象
$resHandler = new ClientResponseHandler();

//-----------------------------
//设置请求参数
//-----------------------------
$reqHandler->init();
$reqHandler->setKey($key);

$reqHandler->setGateUrl("http://mch.tenpay.com/cgi-bin/agent_pay_refund.cgi");
$reqHandler->setParameter("partner", $partner);
$reqHandler->setParameter("start_time", "20111001091010");
$reqHandler->setParameter("end_time", "20111101091010");

//可选系统参数
$reqHandler->setParameter("sign_type", "MD5");
$reqHandler->setParameter("service_version", "1.0");
$reqHandler->setParameter("input_charset", "GBK");
$reqHandler->setParameter("sign_key_index", "1");
//可选业务参数
$reqHandler->setParameter("bank_type", "");		
$reqHandler->setParameter("rec_bankacc", "");		
$reqHandler->setParameter("rec_name", "");		



//-----------------------------
//设置通信参数
//-----------------------------

$httpClient->setTimeOut(5);
//设置请求内容
$httpClient->setReqContent($reqHandler->getRequestURL());

//后台调用
if($httpClient->call()) {
	//设置结果参数
	$resHandler->setContent($httpClient->getResContent());
	$resHandler->setKey($key);

	//判断签名及结果
	//只有签名正确并且retcode为0才是请求成功
	if($resHandler->isTenpaySign() && $resHandler->getParameter("retcode") == "0" ) {
		//取结果参数做业务处理
		
		
		
		echo "OK,退票查询成功" . "<br>";
		
		
	} else {
		//错误时，返回结果可能没有签名，记录retcode、retmsg看失败详情。
		echo "验证签名失败 或 业务错误信息:retcode=" . $resHandler->getParameter("retcode"). ",retmsg=" . $resHandler->getParameter("retmsg") . "<br>";
	}
	
} else {
	//后台调用通信失败
	echo "call err:" . $httpClient->getResponseCode() ."," . $httpClient->getErrInfo() . "<br>";
	//有可能因为网络原因，请求已经处理，但未收到应答。
}


//调试信息,建议把请求、应答内容、debug信息，通信返回码写入日志，方便定位问题

echo "<br>------------------------------------------------------<br>";
echo "http res:" . $httpClient->getResponseCode() . "," . $httpClient->getErrInfo() . "<br>";
echo "req:" . htmlentities($reqHandler->getRequestURL(), ENT_NOQUOTES, "GB2312") . "<br><br>";
echo "res:" . htmlentities($resHandler->getContent(), ENT_NOQUOTES, "GB2312") . "<br><br>";
echo "reqdebug:" . $reqHandler->getDebugInfo() . "<br><br>" ;
echo "resdebug:" . $resHandler->getDebugInfo() . "<br><br>";


?>


</body>
</html>
