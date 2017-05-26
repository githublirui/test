<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=gbk">
	<title>�û���Ϣ��̨����ʾ��</title>
</head>
<body>

<?php
//---------------------------------------------------------
//�Ƹ�ͨ��ѯ��̨����ʾ�����̻����մ��ĵ����п�������
//---------------------------------------------------------

require ("classes/BaseSplitRequestHandler.class.php");
require ("classes/client/ClientResponseHandler.class.php");
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
$resHandler = new ClientResponseHandler();

//-----------------------------
//�����������
//-----------------------------
$reqHandler->init();
$reqHandler->setKey($key);
$reqHandler->setGateURL("https://www.tenpay.com/cgi-bin/v4.0/user_info_check.cgi");

//-----------------------------
//����֧������
//-----------------------------
$reqHandler->setParameter("cmdno", "91");
$reqHandler->setParameter("spid", $bargainor_id);		//�̻���
$reqHandler->setParameter("uin", "93310");				//�û��Ƹ�ͨ�˺�
$reqHandler->setParameter("name", "ׯ׿��");	//�û�����


//-----------------------------
//����ͨ�Ų���
//-----------------------------
$httpClient->setReqContent($reqHandler->getRequestURL());

//��̨����
if($httpClient->call()) {
	//���ý������
	$resHandler->setContent($httpClient->getResContent());
	$resHandler->setKey($key);

	//�ж�ǩ�������
	if($resHandler->isTenpaySign() && $resHandler->getParameter("retcd") == "0" ) {
		//ȡ���������ҵ����
		echo "OK,retcd=" . $resHandler->getParameter("retcd") . "<br>";
		echo "retmsg=" . $resHandler->getParameter("retmsg") . "<br>";;
		
		
	} else {
		//����ʱ�����ؽ��δǩ����
		//�����ʽ�����δȷ�Ͻ���ģ���ʹ��ԭ�����������·���ȷ�Ͻ���������β���
		echo "��֤ǩ��ʧ�� �� ҵ�������Ϣ:" . $resHandler->getParameter("retcd") . "," . $resHandler->getParameter("retmsg") . "<br>";
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
