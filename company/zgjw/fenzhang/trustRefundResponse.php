<?php

//---------------------------------------------------------
//�Ƹ�ͨί���˿��ϵ�仯Ӧ�𣨴���ص���ʾ�����̻����մ��ĵ����п�������
//---------------------------------------------------------

require_once ("./classes/TrustRefundResponseHandler.class.php");

/* ��Կ */
$key = "82d2f8b9fd7695aec51415ab2900a755";

/* ����֧��Ӧ����� */
$resHandler = new TrustRefundResponseHandler();
$resHandler->setKey($key);

//�ж�ǩ��
if($resHandler->isTenpaySign()) {
	//�Ƹ�ͨ�˺�
	$uin = $resHandler->getParameter("uin");
	//״̬
	$status = $resHandler->getParameter("status");
	

	if( "1" == $status ) {
		//������ӹ�ϵ�߼�
		echo "<br/>" . "ǩԼ�ɹ�" . "<br/>";
	} else if( "2" == $status ) {
		//��������ϵ�߼�
		echo "<br/>" . "�ѳ���ǩԼ" . "<br/>";
	}
	
	
} else {
	echo "<br/>" . "��֤ǩ��ʧ��" . "<br/>";
}

//��ȡdebug��Ϣ,����debug��Ϣд����־�����㶨λ����
//echo $resHandler->getDebugInfo();

?>