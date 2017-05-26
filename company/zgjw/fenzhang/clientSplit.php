<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=gbk">
	<title>分帐后台调用示例</title>
</head>
<body>

<?php
//---------------------------------------------------------
//财付通分帐后台调用示例，商户按照此文档进行开发即可
//---------------------------------------------------------

require ("classes/BaseSplitRequestHandler.class.php");
require ("classes/client/ScriptClientResponseHandler.class.php");
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
$resHandler = new ScriptClientResponseHandler();

//-----------------------------
//设置请求参数
//-----------------------------
$reqHandler->init();
$reqHandler->setKey($key);
$reqHandler->setGateURL("https://mch.tenpay.com/cgi-bin/split.cgi");

//-----------------------------
//设置支付参数
//-----------------------------
$reqHandler->setParameter("cmdno", "3");
$reqHandler->setParameter("version", "4");
$reqHandler->setParameter("fee_type", "1");
$reqHandler->setParameter("bargainor_id", $bargainor_id);		//商户号
$reqHandler->setParameter("sp_billno", "0324438786");				//商家订单号
$reqHandler->setParameter("transaction_id", "1900000107201005110324438786");	//财付通交易单号
$reqHandler->setParameter("return_url", "http://127.0.0.1/");			//后台系统调用，必现填写为http://127.0.0.1/
$reqHandler->setParameter("total_fee", "2");				//商品金额,以分为单位
//业务类型
$reqHandler->setParameter("bus_type", "97");
/**
 * 业务参数
 * 帐号^分帐金额^角色
 * 角色说明:	1:供应商 2:平台服务方 4:独立分润方
 * 帐号1^分帐金额1^角色1|帐号2^分帐金额2^角色2|...
 */
$reqHandler->setParameter("bus_args", "93310^1^1|68084040^1^2");
//行业描述信息
$reqHandler->setParameter("bus_desc","12345^深圳-上海^1^fady^庄^13800138000");


//-----------------------------
//设置通信参数
//-----------------------------
//设置PEM证书，pfx证书转pem方法：openssl pkcs12 -in 2000000501.pfx  -out 2000000501.pem
//证书必须放在用户下载不到的目录，避免证书被盗取
$httpClient->setCertInfo("C:\\xampp\\htdocs\\tenpay_split\\key\\1900000107.pem", "1900000107");
//设置CA
$httpClient->setCaInfo("C:\\xampp\\htdocs\\tenpay_split\\key\\cacert.pem");
//设置请求内容
$httpClient->setReqContent($reqHandler->getRequestURL());

//后台调用
if($httpClient->call()) {
	//设置结果参数
	$resHandler->setContent($httpClient->getResContent());
	$resHandler->setKey($key);

	//判断签名及结果
	if($resHandler->isTenpaySign() && $resHandler->getParameter("pay_result") == "0" ) {
		//取结果参数做业务处理
		echo "OK,transaction_id=" . $resHandler->getParameter("transaction_id") . "<br>";
		
		
	} else {
		//错误时，返回结果未签名。
		//如包格式错误或未确认结果的，请使用原来订单号重新发起，确认结果，避免多次操作
		echo "验证签名失败 或 业务错误信息:" . $resHandler->getParameter("pay_result") . "," . $resHandler->getParameter("pay_info") . "<br>";
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
