<?php
//---------------------------------------------------------
//�Ƹ�ͨ�Ƹ�ͨ����ί���˿��ϵ����ʾ�����̻����մ��ĵ����п�������
//---------------------------------------------------------

require_once ("classes/PayRequestHandler.class.php");

/* �̻��� */
$bargainor_id = "1900000107";

//�����URL
$reqUrl = "https://www.tenpay.com/cgi-bin/trust/showtrust_refund.cgi?spid=" . $bargainor_id;


?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=gbk">
	<title>�Ƹ�ͨ����ί���˿��ϵ��ʾ</title>
</head>
<body>
<br/><a href="<?php echo $reqUrl ?>" target="_blank">����ί���˿��ϵ</a>
</body>
</html>
