<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=gbk">
	<title>�Ƹ�ͨ�������д����ӿڳ�����ʾ</title>
</head>
<body>

<?php
require_once ("classes/client/TenpayHttpClient.class.php");
require_once ("classes/client/DirectTransClientRequestHandler.class.php");
require_once ("classes/client/DirectTransClientResponseHandler.class.php");

//��Կ
$key = "82d2f8b9fd7695aec51415ab2900a755";
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
$reqHandler->setParameter("op_code", "1014");			
$reqHandler->setParameter("service_version", "1.1");					
$reqHandler->setParameter("op_name", "batch_draw_query");	
$reqHandler->setParameter("op_user", "1900000107");	
$reqHandler->setParameter("op_passwd", "111111");	
$reqHandler->setParameter("op_time", "20110823105511000");
$reqHandler->setParameter("sp_id", "1900000107");	
$reqHandler->setParameter("package_id", "20110823003galen");
$reqHandler->setParameter("client_ip", "119.147.78.6");



//���÷�������ͨ��XML���ò���
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
//����ͨ�Ų���
//-----------------------------
//����PEM֤�飬pfx֤��תpem������openssl pkcs12 -in 1900000107.pfx  -out 1900000107.pem
//֤���������û����ز�����Ŀ¼������֤�鱻��ȡ
$httpClient->setCertInfo("e:/1900000107.pem", "1900000107");
//����CA
$httpClient->setCaInfo("e:/cacert.pem");
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

echo "<br>------------------------------------------------------<br>";
echo "req:" . htmlentities($reqHandler->getRequestURL(), ENT_NOQUOTES, "GB2312") . "<br><br>";
echo "res:" . htmlentities($resHandler->getContent(), ENT_NOQUOTES, "GB2312") . "<br><br>";
echo "reqdebug:" . htmlentities($reqHandler->getDebugInfo(), ENT_NOQUOTES, "GB2312") . "<br><br>" ;



?>


</body>
</html>