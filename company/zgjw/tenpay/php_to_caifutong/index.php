<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=gbk">
	<title>�Ƹ�ͨ�м������ת�˽ӿڳ�����ʾ</title>
</head>
<body>

<?php
require_once ("classes/client/TenpayHttpClient.class.php");
require_once ("classes/client/DirectTransClientRequestHandler.class.php");
require_once ("classes/client/DirectTransClientResponseHandler.class.php");

//��Կ
$key = "213a4887241e0898494b4a4b86a3ac8d";
/* ����֧��������� */
$reqHandler = new DirectTransClientRequestHandler();
//ͨ�Ŷ���
$httpClient = new TenpayHttpClient();
//Ӧ�����
$resHandler = new DirectTransClientResponseHandler();

//-----------------------------
//�����������
//-----------------------------
$reqHandler->init();
$reqHandler->setKey($key);

//���÷���һ�������������
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
//setParameterֻ������һ���������¼���xml��Ϊ�ϼ��Ĳ���
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
//  <rec_name>ׯ׿��</rec_name>
//  <cur_type>1</cur_type>
//  <pay_amt>1</pay_amt>
//  <desc>fady9</desc>
//</record>



//���÷�������ͨ��XML���ò���
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
			<rec_name>ׯ׿��</rec_name>
			<cur_type>1</cur_type>
			<pay_amt>2</pay_amt>
			<desc>fady6</desc>
		</record>
		<record>
			<serial>2</serial>
			<rec_acc>93310</rec_acc>
			<rec_name>ׯ׿��</rec_name>
			<cur_type>1</cur_type>
			<pay_amt>1</pay_amt>
			<desc>fady9</desc>
		</record>
	</record_set>
</root>";
$reqHandler->setAllParameterFromXml($xml);
*/

//-----------------------------
//����ͨ�Ų���
//-----------------------------
//����PEM֤�飬pfx֤��תpem������openssl pkcs12 -in 2000000501.pfx  -out 2000000501.pem
//֤���������û����ز�����Ŀ¼������֤�鱻��ȡ
$httpClient->setCertInfo("1217091801_20130904192528.pem", "1217091801");
//����CA
$httpClient->setCaInfo("cacert.pem");
//������������
$httpClient->setReqContent($reqHandler->getRequestURL());
//���ó�ʱ
$httpClient->setTimeOut(20);

if($httpClient->call()) {
	//ȡ���������ҵ����
	$resHandler->setContent($httpClient->getResContent());
	$resHandler->setKey($key);
	
	if($resHandler->getParameter("retcode")== "0" || $resHandler->getParameter("retcode") == "00") {
		//ȡ���������ҵ����
		echo "OK,package_id=" . $resHandler->getParameter("package_id") . "<br>";
		
		
	} else {
		//����ʱ�����ؽ��δǩ����
		//�����ʽ�����δȷ�Ͻ���ģ���ʹ��ԭ�����κ����·���ȷ�Ͻ���������β���
		echo "ҵ�������Ϣ:" . $resHandler->getParameter("retcode") . "," . $resHandler->getParameter("retmsg") . "<br>";
	}
	
	
} else {
	//��̨����ͨ��ʧ��
	echo "call err:" . $httpClient->getErrInfo() . "<br>";
	//�п�����Ϊ����ԭ�������Ѿ�������δ�յ�Ӧ��
}


//������Ϣ
/*
echo "<br>------------------------------------------------------<br>";
echo "req:" . htmlentities($reqHandler->getRequestURL(), ENT_NOQUOTES, "GB2312") . "<br><br>";
echo "res:" . htmlentities($resHandler->getContent(), ENT_NOQUOTES, "GB2312") . "<br><br>";
echo "reqdebug:" . htmlentities($reqHandler->getDebugInfo(), ENT_NOQUOTES, "GB2312") . "<br><br>" ;
*/

?>

</body>
</html>