<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=gbk">
	<title>财付通中间件批量转账接口程序演示</title>
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
$reqHandler->setParameter("op_code", "1000");						
$reqHandler->setParameter("op_name", "batch_transfer");	
$reqHandler->setParameter("op_user", "1217091801");	
$reqHandler->setParameter("op_passwd", "111111");	
$reqHandler->setParameter("op_time", "2009071515501000");
$reqHandler->setParameter("sp_id", "1217091801");	
$reqHandler->setParameter("package_id", "20090713004");
$reqHandler->setParameter("total_num", "2");
$reqHandler->setParameter("total_amt", "1");
$reqHandler->setParameter("client_ip", '106.3.78.224');
$reqHandler->setParameter("version", "2");	
//setParameter只能设置一级参数，下级的xml当为上级的参数
$reqHandler->setParameter("record_set", "
<record>
  <serial>1</serial>
  <rec_acc>270230194</rec_acc>
  <rec_name>ff</rec_name>
  <cur_type>1</cur_type>
  <pay_amt>1</pay_amt>
  <desc>fady6</desc>
</record>
<record>
  <serial>2</serial>
  <rec_acc>270230195</rec_acc>
  <rec_name>ff</rec_name>
  <cur_type>1</cur_type>
  <pay_amt>1</pay_amt>
  <desc>fady6</desc>
</record>
");
//<serial>2</serial>
//  <rec_acc>93310</rec_acc>
//  <rec_name>庄卓骏</rec_name>
//  <cur_type>1</cur_type>
//  <pay_amt>1</pay_amt>
//  <desc>fady9</desc>
//</record>



//设置方法二：通过XML设置参数
/*
$xml = "<?xml version=\"1.0\" encoding=\"GB2312\" ?>
<root>
	<op_code>1000</op_code>
	<op_name>batch_transfer</op_name>
	<op_user>2000000501</op_user>
	<op_passwd>111111</op_passwd>
	<op_time>2009071515501000</op_time>
	<sp_id>2000000501</sp_id>
	<package_id>20090713003</package_id>
	<total_num>2</total_num>
	<total_amt>3</total_amt>
	<client_ip>172.25.39.185</client_ip>
    <version>2</version >
	<record_set>
		<record>
			<serial>1</serial>
			<rec_acc>68084040</rec_acc>
			<rec_name>庄卓骏</rec_name>
			<cur_type>1</cur_type>
			<pay_amt>2</pay_amt>
			<desc>fady6</desc>
		</record>
		<record>
			<serial>2</serial>
			<rec_acc>93310</rec_acc>
			<rec_name>庄卓骏</rec_name>
			<cur_type>1</cur_type>
			<pay_amt>1</pay_amt>
			<desc>fady9</desc>
		</record>
	</record_set>
</root>";
$reqHandler->setAllParameterFromXml($xml);
*/

//-----------------------------
//设置通信参数
//-----------------------------
//设置PEM证书，pfx证书转pem方法：openssl pkcs12 -in 2000000501.pfx  -out 2000000501.pem
//证书必须放在用户下载不到的目录，避免证书被盗取
$httpClient->setCertInfo("1217091801_20130904192528.pem", "1217091801");
//设置CA
$httpClient->setCaInfo("cacert.pem");
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
/*
echo "<br>------------------------------------------------------<br>";
echo "req:" . htmlentities($reqHandler->getRequestURL(), ENT_NOQUOTES, "GB2312") . "<br><br>";
echo "res:" . htmlentities($resHandler->getContent(), ENT_NOQUOTES, "GB2312") . "<br><br>";
echo "reqdebug:" . htmlentities($reqHandler->getDebugInfo(), ENT_NOQUOTES, "GB2312") . "<br><br>" ;
*/

?>

</body>
</html>