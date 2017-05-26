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
$key = "213a4887241e0898494b4a4b86a3ac8d";
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
$reqHandler->setParameter("op_code", "1013");						
$reqHandler->setParameter("op_name", "batch_draw");	
$reqHandler->setParameter("op_user", "1900000107");	
$reqHandler->setParameter("op_passwd", "111111");	
$reqHandler->setParameter("op_time", "20110823105511000");
$reqHandler->setParameter("sp_id", "1900000107");	
$reqHandler->setParameter("package_id", "20110823001");
$reqHandler->setParameter("total_num", "2");
$reqHandler->setParameter("total_amt", "3");
$reqHandler->setParameter("client_ip", "119.147.78.6");
//setParameter只能设置一级参数，下级的xml当为上级的参数
$reqHandler->setParameter("record_set", "
<record>
  <serial>1</serial>
  <rec_bankacc>6225887811111111</rec_bankacc>
  <bank_type>1001</bank_type>
  <rec_name>张三</rec_name>
  <pay_amt>1</pay_amt>
  <acc_type>1</acc_type>
  <area>20</area>
  <city>755</city>
  <subbank_name>招商银行深圳泰然金谷支行</subbank_name>
  <desc>代付测试</desc>
  <recv_mobile>13631511111</recv_mobile>
 </record>
 
 <record>
   <serial>2</serial>
   <rec_bankacc>6225887822222222</rec_bankacc>
   <bank_type>1001</bank_type>
   <rec_name>李四</rec_name>
   <pay_amt>1</pay_amt>
   <acc_type>1</acc_type>
   <area>20</area>
   <city>755</city>
   <subbank_name>招商银行深圳泰然金谷支行</subbank_name>
   <desc>代付测试</desc>
   <recv_mobile>13631522222</recv_mobile>
 </record>");



//设置方法二：通过XML设置参数
/*
$xml = "<?xml version=\"1.0\" encoding=\"GB2312\" ?>
<root>
	<op_code>1013</op_code>
	<op_name>batch_draw</op_name>
	<op_user>1900000107</op_user>
	<op_passwd>111111</op_passwd>
	<op_time>20110823105511000</op_time>
	<sp_id>1900000107</sp_id>
	<package_id>20110823001</package_id>
	<total_num>2</total_num>
	<total_amt>3</total_amt>
	<client_ip>10.6.35.42</client_ip>
	<record_set>
		<record>
			<serial>1</serial>
		  <rec_bankacc>6225887811111111</rec_bankacc>
	    <bank_type>1001</bank_type>
	    <rec_name>张三</rec_name>
	    <pay_amt>1</pay_amt>
	    <acc_type>1</acc_type>
	    <area>20</area>
	    <city>755</city>
	    <subbank_name>招商银行深圳泰然金谷支行</subbank_name>
		  <desc>代付测试</desc>
	    <recv_mobile>13631511111</recv_mobile>
		</record>
		<record>
			<serial>2</serial>
		  <rec_bankacc>6225887822222222</rec_bankacc>
	    <bank_type>1001</bank_type>
	    <rec_name>李四</rec_name>
	    <pay_amt>2</pay_amt>
	    <acc_type>1</acc_type>
	    <area>20</area>
	    <city>755</city>
	    <subbank_name>招商银行深圳泰然金谷支行</subbank_name>
		  <desc>代付测试</desc>
	    <recv_mobile>13631522222</recv_mobile>
		</record>
	</record_set>
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