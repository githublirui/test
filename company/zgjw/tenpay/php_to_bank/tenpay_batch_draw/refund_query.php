<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=gbk">
	<title>�����˿��ѯ����ʾ��</title>
</head>
<body>

<?php
//---------------------------------------------------------
//�Ƹ�ͨ�����˿��ѯ����ʾ�����̻����մ��ĵ����п�������
//---------------------------------------------------------

require ("classes/RequestHandler.class.php");
require ("classes/client/ClientResponseHandler.class.php");
require ("classes/client/TenpayHttpClient.class.php");

/* �̻��� */
$partner = "1900000107";


/* ��Կ */
$key = "82d2f8b9fd7695aec51415ab2900a755";




/* ����֧��������� */
$reqHandler = new RequestHandler();

//ͨ�Ŷ���
$httpClient = new TenpayHttpClient();

//Ӧ�����
$resHandler = new ClientResponseHandler();

//-----------------------------
//�����������
//-----------------------------
$reqHandler->init();
$reqHandler->setKey($key);

$reqHandler->setGateUrl("http://mch.tenpay.com/cgi-bin/agent_pay_refund.cgi");
$reqHandler->setParameter("partner", $partner);
$reqHandler->setParameter("start_time", "20111001091010");
$reqHandler->setParameter("end_time", "20111101091010");

//��ѡϵͳ����
$reqHandler->setParameter("sign_type", "MD5");
$reqHandler->setParameter("service_version", "1.0");
$reqHandler->setParameter("input_charset", "GBK");
$reqHandler->setParameter("sign_key_index", "1");
//��ѡҵ�����
$reqHandler->setParameter("bank_type", "");		
$reqHandler->setParameter("rec_bankacc", "");		
$reqHandler->setParameter("rec_name", "");		



//-----------------------------
//����ͨ�Ų���
//-----------------------------

$httpClient->setTimeOut(5);
//������������
$httpClient->setReqContent($reqHandler->getRequestURL());

//��̨����
if($httpClient->call()) {
	//���ý������
	$resHandler->setContent($httpClient->getResContent());
	$resHandler->setKey($key);

	//�ж�ǩ�������
	//ֻ��ǩ����ȷ����retcodeΪ0��������ɹ�
	if($resHandler->isTenpaySign() && $resHandler->getParameter("retcode") == "0" ) {
		//ȡ���������ҵ����
		
		
		
		echo "OK,��Ʊ��ѯ�ɹ�" . "<br>";
		
		
	} else {
		//����ʱ�����ؽ������û��ǩ������¼retcode��retmsg��ʧ�����顣
		echo "��֤ǩ��ʧ�� �� ҵ�������Ϣ:retcode=" . $resHandler->getParameter("retcode"). ",retmsg=" . $resHandler->getParameter("retmsg") . "<br>";
	}
	
} else {
	//��̨����ͨ��ʧ��
	echo "call err:" . $httpClient->getResponseCode() ."," . $httpClient->getErrInfo() . "<br>";
	//�п�����Ϊ����ԭ�������Ѿ�������δ�յ�Ӧ��
}


//������Ϣ,���������Ӧ�����ݡ�debug��Ϣ��ͨ�ŷ�����д����־�����㶨λ����

echo "<br>------------------------------------------------------<br>";
echo "http res:" . $httpClient->getResponseCode() . "," . $httpClient->getErrInfo() . "<br>";
echo "req:" . htmlentities($reqHandler->getRequestURL(), ENT_NOQUOTES, "GB2312") . "<br><br>";
echo "res:" . htmlentities($resHandler->getContent(), ENT_NOQUOTES, "GB2312") . "<br><br>";
echo "reqdebug:" . $reqHandler->getDebugInfo() . "<br><br>" ;
echo "resdebug:" . $resHandler->getDebugInfo() . "<br><br>";


?>


</body>
</html>
