<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=gbk">
	<title>������ѯ��̨����ʾ��</title>
</head>
<body>

<?php
//---------------------------------------------------------
//�Ƹ�ͨ�������̨����ʾ�����̻����մ��ĵ����п�������
//---------------------------------------------------------

require ("classes/RequestHandler.class.php");
require ("classes/client/ClientResponseHandler.class.php");
require ("classes/client/TenpayHttpClient.class.php");

/* �̻��� */
$partner = "1900000109";


/* ��Կ */
$key = "8934e7d15453e97507ef794cf7b0519d";




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

$reqHandler->setGateUrl("https://gw.tenpay.com/gateway/normalorderquery.xml");
$reqHandler->setParameter("partner", $partner);
//out_trade_no��transaction_id����һ�����ͬʱ����ʱtransaction_id����
$reqHandler->setParameter("out_trade_no", "201009151621261820");
//$reqHandler->setParameter("transaction_id", "2000000501201004300000000442");			



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
		//�̻�������
		$out_trade_no = $resHandler->getParameter("out_trade_no");
		
		//�Ƹ�ͨ������
		$transaction_id = $resHandler->getParameter("transaction_id");
		
		//���,�Է�Ϊ��λ
		$total_fee = $resHandler->getParameter("total_fee");
		
		//֧�����
		$trade_state = $resHandler->getParameter("trade_state");
		
		//֧���ɹ�
		if($trade_state == "0") {
		}
		
		echo "OK,trade_state=" . $trade_state . ",is_split=" . $resHandler->getParameter("is_split") . ",is_refund=" . $resHandler->getParameter("is_refund") . "<br>";
		
		
	} else {
		//����ʱ�����ؽ������û��ǩ������¼retcode��retmsg��ʧ�����顣
		echo "��֤ǩ��ʧ�� �� ҵ�������Ϣ:retcode=" . $resHandler->getParameter("retcode"). ",retmsg=" . $resHandler->getParameter("retmsg") . "<br>";
	}
	
} else {
	//��̨����ͨ��ʧ��
	echo "call err:" . $httpClient->getResponseCode() ."," . $httpClient->getErrInfo() . "<br>";
	//�п�����Ϊ����ԭ�������Ѿ���������δ�յ�Ӧ��
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