<?php

//---------------------------------------------------------
//�Ƹ�ͨ����֧������ʾ�����̻����մ��ĵ����п�������
//---------------------------------------------------------

require ("classes/ResponseHandler.class.php");

/* ��Կ */
$key = "82d2f8b9fd7695aec51415ab2900a755";

/* ����֧��Ӧ����� */
$resHandler = new ResponseHandler();
$resHandler->setKey($key);

//֧�������״̬��
$pay_result = $resHandler->getParameter("pay_result");

//֧����Ϣ��������Ϣ
$pay_info = $resHandler->getParameter("pay_info");

//�ж�ǩ��
if($resHandler->isTenpaySign()) {
	
	//���׵���
	$transaction_id = $resHandler->getParameter("transaction_id");
	
	//���,�Է�Ϊ��λ
	$total_fee = $resHandler->getParameter("total_fee");
	
	if( "0" == $pay_result ) {
	
		//------------------------------
		//����ҵ��ʼ
		//------------------------------
		
		//ע�⽻�׵���Ҫ�ظ�����
		//ע���жϷ��ؽ��
		
		//------------------------------
		//����ҵ�����
		//------------------------------	
		
		//����doShow, ��ӡmetaֵ��js����,���߲Ƹ�ͨ����ɹ�,�����û��������ʾ$showҳ��.
		$show = "http://localhost/tenpay_split/show.php";
		$resHandler->doShow($show);
	
	} else {
		//�������ɹ�����
		echo "<br/>" . "֧��ʧ��" . "<br/>";
	}
	
} else {
	echo "<br/>" . "��֤ǩ��ʧ��" . "<br/>";
	
	echo "<br/>" . "״̬�룺" . $pay_result . "<br/>";
	
	echo "<br/>" . "������Ϣ��" . $pay_info . "<br/>";	
}

//��ȡdebug��Ϣ,�����debug��Ϣд����־�����㶨λ����
//echo $resHandler->getDebugInfo();

?>
