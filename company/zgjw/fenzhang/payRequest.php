<?php
//---------------------------------------------------------
//�Ƹ�ͨ��ʱ����֧������ʾ�����̻����մ��ĵ����п�������
//---------------------------------------------------------

require_once ("classes/PayRequestHandler.class.php");

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
$reqHandler = new PayRequestHandler();
$reqHandler->init();
$reqHandler->setKey($key);

//----------------------------------------
//����֧������
//----------------------------------------
$reqHandler->setParameter("bargainor_id", $bargainor_id);			//�̻���
$reqHandler->setParameter("sp_billno", $sp_billno);					//�̻�������
$reqHandler->setParameter("transaction_id", $transaction_id);		//�Ƹ�ͨ���׵���
$reqHandler->setParameter("total_fee", "100");					//��Ʒ�ܽ��,�Է�Ϊ��λ
$reqHandler->setParameter("return_url", "http://127.0.0.1:8080/tenpay_split/payResponse.php");				//���ش����ַ	
$reqHandler->setParameter("bank_type", "0");		

$reqHandler->setParameter("desc", "����");	//��Ʒ����

//�û��Ĺ���ip,����ʱ��д127.0.0.1ֻ��Ҫ10�����½���
$reqHandler->setParameter("spbill_create_ip", $_SERVER['REMOTE_ADDR']);

//�����URL
$reqUrl = $reqHandler->getRequestURL();

//��ȡdebug��Ϣ,����������debug��Ϣд����־�����㶨λ����
//$debugInfo = $reqHandler->getDebugInfo();
//echo "<br/>" . $reqUrl . "<br/>";
//echo "<br/>" . $debugInfo . "<br/>";




?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=gbk">
	<title>�Ƹ�ͨ��ʱ���ʳ�����ʾ</title>
</head>
<body>
<br/><a href="<?php echo $reqUrl ?>" target="_blank">�Ƹ�֧ͨ��</a>
</body>
</html>
