<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=gbk">
	<title>用户信息后台调用示例</title>
</head>
<body>

<?php
//---------------------------------------------------------
//财付通查询后台调用示例，商户按照此文档进行开发即可
//---------------------------------------------------------

require ("classes/BaseSplitRequestHandler.class.php");
require ("classes/client/ClientResponseHandler.class.php");
require ("classes/client/TenpayHttpClient.class.php");

/* 商户号 */
$bargainor_id = "1900000107";
/* 密钥 */
$key = "82d2f8b9fd7695aec51415ab2900a755";


/* 创建支付请求对象 */
$reqHandler = new BaseSplitRequestHandler();
//通信对象
$httpClient = new TenpayHttpClient();
//应答对象
$resHandler = new ClientResponseHandler();

//-----------------------------
//设置请求参数
//-----------------------------
$reqHandler->init();
$reqHandler->setKey($key);
$reqHandler->setGateURL("https://www.tenpay.com/cgi-bin/v4.0/user_info_check.cgi");

//-----------------------------
//设置支付参数
//-----------------------------
$reqHandler->setParameter("cmdno", "91");
$reqHandler->setParameter("spid", $bargainor_id);		//商户号
$reqHandler->setParameter("uin", "93310");				//用户财付通账号
$reqHandler->setParameter("name", "庄卓骏");	//用户姓名


//-----------------------------
//设置通信参数
//-----------------------------
$httpClient->setReqContent($reqHandler->getRequestURL());

//后台调用
if($httpClient->call()) {
	//设置结果参数
	$resHandler->setContent($httpClient->getResContent());
	$resHandler->setKey($key);

	//判断签名及结果
	if($resHandler->isTenpaySign() && $resHandler->getParameter("retcd") == "0" ) {
		//取结果参数做业务处理
		echo "OK,retcd=" . $resHandler->getParameter("retcd") . "<br>";
		echo "retmsg=" . $resHandler->getParameter("retmsg") . "<br>";;
		
		
	} else {
		//错误时，返回结果未签名。
		//如包格式错误或未确认结果的，请使用原来订单号重新发起，确认结果，避免多次操作
		echo "验证签名失败 或 业务错误信息:" . $resHandler->getParameter("retcd") . "," . $resHandler->getParameter("retmsg") . "<br>";
	}
	
} else {
	//后台调用通信失败
	echo "call err:" . $httpClient->getErrInfo() . "<br>";
	//有可能因为网络原因，请求已经处理，但未收到应答。
}


//调试信息,建议把请求、应答内容、debug信息，通信返回码写入日志，方便定位问题
/*
echo "<br>------------------------------------------------------<br>";
echo "http res:" . $httpClient->getResponseCode() . "," . $httpClient->getErrInfo() . "<br>";
echo "req:" . htmlentities($reqHandler->getRequestURL(), ENT_NOQUOTES, "GB2312") . "<br><br>";
echo "res:" . htmlentities($resHandler->getContent(), ENT_NOQUOTES, "GB2312") . "<br><br>";
echo "reqdebug:" . $reqHandler->getDebugInfo() . "<br><br>" ;
echo "resdebug:" . $resHandler->getDebugInfo() . "<br><br>";
*/

?>


</body>
</html>
