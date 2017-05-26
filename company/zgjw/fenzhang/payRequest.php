<?php
//---------------------------------------------------------
//财付通即时到帐支付请求示例，商户按照此文档进行开发即可
//---------------------------------------------------------

require_once ("classes/PayRequestHandler.class.php");

/* 商户号 */
$bargainor_id = "1900000107";
/* 密钥 */
$key = "82d2f8b9fd7695aec51415ab2900a755";


//---------------生成订单号 开始------------------------
$strDate = date("Ymd");
$strTime = date("His");
//4位随机数
$randNum = rand(1000, 9999);
//10位序列号,可以自行调整。
$strReq = $strTime . $randNum;
// 商家订单号,长度若超过32位，取前32位。财付通只记录商家订单号，不保证唯一。
$sp_billno = $strReq;
// 财付通交易单号，规则为：10位商户号+8位时间（YYYYmmdd)+10位流水号 ,商户根据自己情况调整，只要保证唯一及符合规则就行
$transaction_id = $bargainor_id . $strDate . $strReq;
//---------------生成订单号 结束------------------------



/* 创建支付请求对象 */
$reqHandler = new PayRequestHandler();
$reqHandler->init();
$reqHandler->setKey($key);

//----------------------------------------
//设置支付参数
//----------------------------------------
$reqHandler->setParameter("bargainor_id", $bargainor_id);			//商户号
$reqHandler->setParameter("sp_billno", $sp_billno);					//商户订单号
$reqHandler->setParameter("transaction_id", $transaction_id);		//财付通交易单号
$reqHandler->setParameter("total_fee", "100");					//商品总金额,以分为单位
$reqHandler->setParameter("return_url", "http://127.0.0.1:8080/tenpay_split/payResponse.php");				//返回处理地址	
$reqHandler->setParameter("bank_type", "0");		

$reqHandler->setParameter("desc", "测试");	//商品名称

//用户的公网ip,测试时填写127.0.0.1只需要10分以下交易
$reqHandler->setParameter("spbill_create_ip", $_SERVER['REMOTE_ADDR']);

//请求的URL
$reqUrl = $reqHandler->getRequestURL();

//获取debug信息,建议把请求和debug信息写入日志，方便定位问题
//$debugInfo = $reqHandler->getDebugInfo();
//echo "<br/>" . $reqUrl . "<br/>";
//echo "<br/>" . $debugInfo . "<br/>";




?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=gbk">
	<title>财付通即时到帐程序演示</title>
</head>
<body>
<br/><a href="<?php echo $reqUrl ?>" target="_blank">财付通支付</a>
</body>
</html>
