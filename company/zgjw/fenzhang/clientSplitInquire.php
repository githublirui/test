<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=gbk">
	<title>��ѯ��̨����ʾ��</title>
</head>
<body>

<?php
//---------------------------------------------------------
//�Ƹ�ͨ��ѯ��̨����ʾ�����̻����մ��ĵ����п�������
//---------------------------------------------------------

require ("classes/BaseSplitRequestHandler.class.php");
require ("classes/client/ScriptClientResponseHandler.class.php");
require ("classes/client/TenpayHttpClient.class.php");

/* �̻��� */
$bargainor_id = "1900000107";
/* ��Կ */
$key = "82d2f8b9fd7695aec51415ab2900a755";


/* ����֧��������� */
$reqHandler = new BaseSplitRequestHandler();
//ͨ�Ŷ���
$httpClient = new TenpayHttpClient();
//Ӧ�����
$resHandler = new ScriptClientResponseHandler();

//-----------------------------
//�����������
//-----------------------------
$reqHandler->init();
$reqHandler->setKey($key);
$reqHandler->setGateURL("https://mch.tenpay.com/cgi-bin/inquire_refund.cgi");

//-----------------------------
//����֧������
//-----------------------------
$reqHandler->setParameter("cmdno", "96");
$reqHandler->setParameter("version", "4");
$reqHandler->setParameter("date", date("Ymd"));
$reqHandler->setParameter("bargainor_id", $bargainor_id);		//�̻���
$reqHandler->setParameter("sp_billno", "0304434936");				//�̼Ҷ�����
$reqHandler->setParameter("transaction_id", "1900000107201006070304434936");	//�Ƹ�ͨ���׵���
$reqHandler->setParameter("return_url", "http://127.0.0.1/");			//��̨ϵͳ���ã�������дΪhttp://127.0.0.1/


//-----------------------------
//����ͨ�Ų���
//-----------------------------
//����PEM֤�飬pfx֤��תpem������openssl pkcs12 -in 2000000501.pfx  -out 2000000501.pem
//֤���������û����ز�����Ŀ¼������֤�鱻��ȡ
$httpClient->setCertInfo("C:\\xampp\\htdocs\\tenpay_split\\key\\1900000107.pem", "1900000107");
//����CA
$httpClient->setCaInfo("C:\\xampp\\htdocs\\tenpay_split\\key\\cacert.pem");
//������������
$httpClient->setReqContent($reqHandler->getRequestURL());

//��̨����
if($httpClient->call()) {
	//���ý������
	$resHandler->setContent($httpClient->getResContent());
	$resHandler->setKey($key);

	//�ж�ǩ�������
	if($resHandler->isTenpaySign() && $resHandler->getParameter("pay_result") == "0" ) {
		//ȡ���������ҵ����
		echo "OK,transaction_id=" . $resHandler->getParameter("transaction_id") . "<br>";
		echo "bus_type=" . $resHandler->getParameter("bus_type") . "bus_args=" . $resHandler->getParameter("bus_args") . "<br>";;
		echo "bus_split_refund_args=" . $resHandler->getParameter("bus_split_refund_args") . "<br>";
		echo "bus_platform_refund_args=" . $resHandler->getParameter("bus_platform_refund_args") . "<br>";
		echo "bus_freeze_args=" . $resHandler->getParameter("bus_freeze_args") . "<br>";
		echo "bus_thaw_args=" . $resHandler->getParameter("bus_thaw_args") . "<br>";
		echo "pay_time=" . $resHandler->getParameter("pay_time") . "<br>";
		
		
	} else {
		//����ʱ�����ؽ��δǩ����
		//�����ʽ�����δȷ�Ͻ���ģ���ʹ��ԭ�����������·���ȷ�Ͻ���������β���
		echo "��֤ǩ��ʧ�� �� ҵ�������Ϣ:" . $resHandler->getParameter("pay_result") . "," . $resHandler->getParameter("pay_info") . "<br>";
	}
	
} else {
	//��̨����ͨ��ʧ��
	echo "call err:" . $httpClient->getErrInfo() . "<br>";
	//�п�����Ϊ����ԭ�������Ѿ�������δ�յ�Ӧ��
}


//������Ϣ,���������Ӧ�����ݡ�debug��Ϣ��ͨ�ŷ�����д����־�����㶨λ����
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
