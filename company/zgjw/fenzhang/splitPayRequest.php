<?php
//---------------------------------------------------------
//�Ƹ�֧ͨ������������ʾ�����̻����մ��ĵ����п�������
//---------------------------------------------------------

require ("classes/BaseSplitRequestHandler.class.php");

/* �̻��� */
$bargainor_id = "1900000107";
/* ��Կ */
$key = "82d2f8b9fd7695aec51415ab2900a755";


//---------------���ɶ����� ��ʼ------------------------
$strDate = date("Ymd");
$strTime = date("His");
//4λ�����
$randNum = rand(1000, 9999);
//10λ���к�,�������е�����
$strReq = $strTime . $randNum;
// �̼Ҷ�����,����������32λ��ȡǰ32λ���Ƹ�ֻͨ��¼�̼Ҷ����ţ�����֤Ψһ��
$sp_billno = $strReq;
// �Ƹ�ͨ���׵��ţ�����Ϊ��10λ�̻���+8λʱ�䣨YYYYmmdd)+10λ��ˮ�� ,�̻������Լ����������ֻҪ��֤Ψһ�����Ϲ������
$transaction_id = $bargainor_id . $strDate . $strReq;
//---------------���ɶ����� ����------------------------


/* ����֧��������� */
$reqHandler = new BaseSplitRequestHandler();
$reqHandler->init();
$reqHandler->setKey($key);
$reqHandler->setGateURL("https://www.tenpay.com/cgi-bin/v4.0/pay_set.cgi");

//-----------------------------
//����֧������
//-----------------------------
$reqHandler->setParameter("bank_type", "0");
$reqHandler->setParameter("cmdno", "1");
$reqHandler->setParameter("date", date("Ymd"));
$reqHandler->setParameter("fee_type", "1");
$reqHandler->setParameter("version", "4");

$reqHandler->setParameter("bargainor_id", $bargainor_id);		//�̻���
$reqHandler->setParameter("sp_billno", $sp_billno);				//�̼Ҷ�����
$reqHandler->setParameter("transaction_id", $transaction_id);	//�Ƹ�ͨ���׵���
$reqHandler->setParameter("return_url", "http://127.0.0.1:8080/tenpay_split/splitPayResponse.php");			//֧��֪ͨurl
$reqHandler->setParameter("desc", "֧�������˲���");						//��Ʒ����
$reqHandler->setParameter("total_fee", "1");				//��Ʒ���,�Է�Ϊ��λ



//ҵ������
$reqHandler->setParameter("bus_type", "97");

/**
 * ҵ�����
 * �ʺ�^���ʽ��^��ɫ
 * ��ɫ˵��:	1:��Ӧ�� 2:ƽ̨���� 4:��������
 * �ʺ�1^���ʽ��1^��ɫ1|�ʺ�2^���ʽ��2^��ɫ2|...
 */
$reqHandler->setParameter("bus_args", "68084040^1^1");

//��ҵ������Ϣ
$reqHandler->setParameter("bus_desc","12345^����-�Ϻ�^1^fady^ׯ^13800138000");

//�û��Ĺ���ip,����ʱ��д127.0.0.1ֻ��Ҫ10�����½���
$reqHandler->setParameter("spbill_create_ip", $_SERVER['REMOTE_ADDR']);

//�����URL
$requestUrl = $reqHandler->getRequestURL();

//�ض��򵽲Ƹ�ͨ
//$reqHandler->doSend();

//��ȡdebug��Ϣ,����������debug��Ϣд����־�����㶨λ����
/*
$debugInfo = $reqHandler->getDebugInfo();
echo "<br/>" . $requestUrl . "<br/>";
echo "<br/>" . $debugInfo . "<br/>";
*/



?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=gbk">
	<title>֧��������</title>
</head>
<body>
<br/><a href="<?php echo $requestUrl ?>" target="_blank">�Ƹ�֧ͨ��������</a>
</body>
</html>
